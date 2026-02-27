import { createClient } from '@supabase/supabase-js';

// Initialize Supabase client
const supabaseUrl = import.meta.env.VITE_SUPABASE_URL;
const supabaseAnonKey = import.meta.env.VITE_SUPABASE_ANON_KEY;

if (!supabaseUrl || !supabaseAnonKey) {
    console.warn('Supabase environment variables not configured. Realtime updates disabled.');
}

const supabase = supabaseUrl && supabaseAnonKey 
    ? createClient(supabaseUrl, supabaseAnonKey) 
    : null;

// Track last known values for change detection
let lastAccountValues = null;
let lastLogIds = [];

// DOM Elements
const overallBottlesEl = document.getElementById('overall-bottles');
const plasticBottlesEl = document.getElementById('plastic-bottles');
const metalBottlesEl = document.getElementById('metal-bottles');
const coinsAvailableEl = document.getElementById('coins-available');
const activityLogsEl = document.getElementById('activity-logs');

// Initialize with current values from DOM
function initializeLastValues() {
    if (overallBottlesEl) lastAccountValues = {
        overall_bottles: parseInt(overallBottlesEl.textContent) || 0,
        plastic_bottles: parseInt(plasticBottlesEl.textContent) || 0,
        metal_bottles: parseInt(metalBottlesEl.textContent) || 0,
        coins_available: parseInt(coinsAvailableEl.textContent) || 0,
    };
    
    // Get existing log IDs
    if (activityLogsEl) {
        const logElements = activityLogsEl.querySelectorAll('[data-log-id]');
        lastLogIds = Array.from(logElements).map(el => el.dataset.logId);
    }
}

// Update stat numbers in DOM
function updateStats(account) {
    if (!account) return;
    
    if (overallBottlesEl && account.overall_bottles !== undefined) {
        overallBottlesEl.textContent = account.overall_bottles;
    }
    if (plasticBottlesEl && account.plastic_bottles !== undefined) {
        plasticBottlesEl.textContent = account.plastic_bottles;
    }
    if (metalBottlesEl && account.metal_bottles !== undefined) {
        metalBottlesEl.textContent = account.metal_bottles;
    }
    if (coinsAvailableEl && account.coins_available !== undefined) {
        coinsAvailableEl.textContent = account.coins_available;
    }
}

// Create HTML for a single log entry
function createLogHtml(log) {
    const isPlastic = log.bottle_type === 'plastic';
    const iconBg = isPlastic ? 'bg-purple-100' : 'bg-gray-100';
    const iconColor = isPlastic ? 'text-purple-600' : 'text-gray-600';
    const bottleType = isPlastic ? 'Plastic Detected' : 'Metal Detected';
    const date = new Date(log.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    const coinsHtml = log.coins_earned > 0 
        ? `<p class="text-xs text-green-600 font-medium mt-2">+${log.coins_earned} coins earned</p>` 
        : '';
    
    return `
        <div class="p-6 hover:bg-gray-50 transition-colors" data-log-id="${log.id}">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4">
                    <div class="mt-1">
                        <div class="w-10 h-10 rounded-lg ${iconBg} flex items-center justify-center">
                            <svg class="w-6 h-6 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20m0 0c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">${bottleType}</p>
                        <p class="text-sm text-gray-600 mt-1">${log.description || ''}</p>
                        ${coinsHtml}
                    </div>
                </div>
                <div class="flex items-center space-x-4 ml-4">
                    <span class="text-sm text-gray-500 whitespace-nowrap">${date}</span>
                </div>
            </div>
        </div>
    `;
}

// Update activity logs
function updateLogs(logs) {
    if (!activityLogsEl || !logs) return;
    
    // Check if there are new logs
    const currentIds = logs.map(l => String(l.id));
    const hasNewLogs = currentIds.some(id => !lastLogIds.includes(id));
    
    if (!hasNewLogs) return; // No new logs
    
    // Update lastLogIds
    lastLogIds = currentIds;
    
    // Re-render all logs (newest first)
    const sortedLogs = [...logs].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    activityLogsEl.innerHTML = sortedLogs.map(log => createLogHtml(log)).join('');
}

// Fetch data from Laravel endpoint (polling fallback)
async function fetchDashboardData() {
    try {
        const response = await fetch('/dashboard/data');
        if (!response.ok) return;
        
        const data = await response.json();
        
        if (data.account) {
            // Check if values changed before updating
            if (!lastAccountValues || 
                lastAccountValues.overall_bottles !== data.account.overall_bottles ||
                lastAccountValues.plastic_bottles !== data.account.plastic_bottles ||
                lastAccountValues.metal_bottles !== data.account.metal_bottles ||
                lastAccountValues.coins_available !== data.account.coins_available) {
                
                updateStats(data.account);
                lastAccountValues = { ...data.account };
            }
        }
        
        if (data.logs) {
            updateLogs(data.logs);
        }
    } catch (error) {
        console.error('Error fetching dashboard data:', error);
    }
}

// Subscribe to Supabase realtime changes
async function subscribeToRealtime() {
    if (!supabase) {
        console.log('Supabase not configured, using polling fallback');
        startPolling();
        return;
    }
    
    const accountId = window.ECOPAY_ACCOUNT_ID;
    const userEmail = window.AUTH_EMAIL;
    
    if (!accountId || !userEmail) {
        console.warn('Missing account ID or email, using polling fallback');
        startPolling();
        return;
    }
    
    try {
        // Subscribe to ecopay_accounts changes for this user
        const accountsChannel = supabase
            .channel('ecopay_accounts_changes')
            .on(
                'postgres_changes',
                {
                    event: 'UPDATE',
                    schema: 'public',
                    table: 'ecopay_accounts',
                    filter: `email=eq.${userEmail}`,
                },
                (payload) => {
                    console.log('Account updated!', payload.new);
                    updateStats(payload.new);
                    lastAccountValues = { ...payload.new };
                }
            )
            .subscribe();
        
        // Subscribe to ecopay_activity_logs for this account
        const logsChannel = supabase
            .channel('ecopay_activity_logs_changes')
            .on(
                'postgres_changes',
                {
                    event: 'INSERT',
                    schema: 'public',
                    table: 'ecopay_activity_logs',
                    filter: `account_id=eq.${accountId}`,
                },
                (payload) => {
                    console.log('New activity log!', payload.new);
                    // Fetch latest logs to get proper order
                    fetchDashboardData();
                }
            )
            .subscribe();
        
        console.log('Subscribed to Supabase realtime changes');
        
        // Start polling as backup (every 10 seconds)
        setInterval(fetchDashboardData, 10000);
        
    } catch (error) {
        console.error('Error setting up Supabase realtime:', error);
        startPolling();
    }
}

// Start polling fallback (every 2 seconds)
function startPolling() {
    console.log('Starting polling fallback (every 2 seconds)');
    setInterval(fetchDashboardData, 2000);
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    initializeLastValues();
    subscribeToRealtime();
});
