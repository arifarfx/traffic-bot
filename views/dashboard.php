<?php
$title = "Dashboard - Traffic Bot";
require_once 'layouts/main.php';
?>

<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">Dashboard</h2>
            </div>
        </div>
    </div>
    
    <div class="row mt-3">
        <!-- Statistics Cards -->
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Active Sessions</div>
                    </div>
                    <div class="h1" id="activeSessionsCount">0</div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Total Requests Today</div>
                    </div>
                    <div class="h1" id="totalRequestsCount">0</div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Success Rate</div>
                    </div>
                    <div class="h1" id="successRate">0%</div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Avg Response Time</div>
                    </div>
                    <div class="h1" id="avgResponseTime">0ms</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Traffic Sessions</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Target URL</th>
                                    <th>Status</th>
                                    <th>Requests/s</th>
                                    <th>Duration</th>
                                    <th>Success Rate</th>
                                    <th>Started At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="trafficSessions">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
    setInterval(loadDashboardData, 5000);
});

async function loadDashboardData() {
    try {
        const response = await fetch('/api/traffic/stats', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });
        
        const data = await response.json();
        if (data.status === 'success') {
            updateDashboardStats(data.data);
        }
    } catch (error) {
        console.error('Error loading dashboard data:', error);
    }
}

function updateDashboardStats(stats) {
    document.getElementById('activeSessionsCount').textContent = stats.activeSessions || 0;
    document.getElementById('totalRequestsCount').textContent = stats.totalRequests || 0;
    document.getElementById('successRate').textContent = `${stats.successRate || 0}%`;
    document.getElementById('avgResponseTime').textContent = `${stats.avgResponseTime || 0}ms`;
    
    updateTrafficSessionsTable(stats.recentSessions || []);
}

function updateTrafficSessionsTable(sessions) {
    const tbody = document.getElementById('trafficSessions');
    tbody.innerHTML = '';
    
    sessions.forEach(session => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${session.targetUrl}</td>
            <td><span class="badge bg-${getStatusColor(session.status)}">${session.status}</span></td>
            <td>${session.requestsPerSecond}</td>
            <td>${session.duration}s</td>
            <td>${session.stats.successRate}%</td>
            <td>${new Date(session.startedAt).toLocaleString()}</td>
            <td>
                ${session.status === 'running' ? 
                    `<button class="btn btn-danger btn-sm" onclick="stopTraffic('${session._id}')">Stop</button>` :
                    `<button class="btn btn-secondary btn-sm" disabled>Stopped</button>`
                }
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function getStatusColor(status) {
    switch(status) {
        case 'running': return 'success';
        case 'stopped': return 'danger';
        case 'completed': return 'info';
        default: return 'secondary';
    }
}

async function stopTraffic(id) {
    try {
        const response = await fetch(`/api/traffic/stop/${id}`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });
        
        const data = await response.json();
        if (data.status === 'success') {
            loadDashboardData();
        }
    } catch (error) {
        console.error('Error stopping traffic:', error);
    }
}
</script>