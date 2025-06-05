<?php
$title = "Traffic Control - Traffic Bot";
require_once 'layouts/main.php';
?>

<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">Traffic Control</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newTrafficModal">
                    New Traffic Session
                </button>
            </div>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Traffic Sessions</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Target URL</th>
                                    <th>Status</th>
                                    <th>Requests/s</th>
                                    <th>Duration</th>
                                    <th>Success</th>
                                    <th>Failed</th>
                                    <th>Avg Response</th>
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

<!-- New Traffic Modal -->
<div class="modal modal-blur fade" id="newTrafficModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Traffic Session</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="newTrafficForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Target URL</label>
                        <input type="url" class="form-control" name="targetUrl" required>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Requests per Second</label>
                                <input type="number" class="form-control" name="requestsPerSecond" required min="1">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Duration (seconds)</label>
                                <input type="number" class="form-control" name="duration" required min="1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary ms-auto">
                        Start Traffic Session
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadTrafficSessions();
    setInterval(loadTrafficSessions, 5000);
    
    document.getElementById('newTrafficForm').addEventListener('submit', handleNewTrafficSubmit);
});

async function loadTrafficSessions() {
    try {
        const response = await fetch('/api/traffic/data', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });
        
        const data = await response.json();
        if (data.status === 'success') {
            updateTrafficTable(data.data);
        }
    } catch (error) {
        console.error('Error loading traffic sessions:', error);
    }
}

function updateTrafficTable(sessions) {
    const tbody = document.getElementById('trafficSessions');
    tbody.innerHTML = '';
    
    sessions.forEach(session => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${session._id}</td>
            <td>${session.targetUrl}</td>
            <td><span class="badge bg-${getStatusColor(session.status)}">${session.status}</span></td>
            <td>${session.requestsPerSecond}</td>
            <td>${session.duration}s</td>
            <td>${session.stats.successfulRequests}</td>
            <td>${session.stats.failedRequests}</td>
            <td>${session.stats.averageResponseTime}ms</td>
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

async function handleNewTrafficSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = {
        targetUrl: formData.get('targetUrl'),
        requestsPerSecond: parseInt(formData.get('requestsPerSecond')),
        duration: parseInt(formData.get('duration'))
    };
    
    try {
        const response = await fetch('/api/traffic/start', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        if (result.status === 'success') {
            const modal = bootstrap.Modal.getInstance(document.getElementById('newTrafficModal'));
            modal.hide();
            e.target.reset();
            loadTrafficSessions();
        }
    } catch (error) {
        console.error('Error starting traffic session:', error);
    }
}

function getStatusColor(status) {
    switch(status) {
        case 'running': return 'success';
        case 'stopped': return 'danger';
        case 'completed': return 'info';
        default: return 'secondary';
    }
}
</script>