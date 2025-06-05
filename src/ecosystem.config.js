module.exports = {
  apps: [{
    name: 'traffic-bot',
    script: 'server.js',
    watch: false,
    env: {
      NODE_ENV: 'production',
      PORT: 3000
    },
    error_file: 'logs/error.log',
    out_file: 'logs/output.log',
    log_file: 'logs/combined.log',
    time: true,
    autorestart: true,
    max_restarts: 10,
    restart_delay: 4000
  }]
};
