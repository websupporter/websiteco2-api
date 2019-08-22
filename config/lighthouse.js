module.exports = {
    extends: 'lighthouse:default',
    settings: {
        emulatedFormFactor: 'desktop',
        onlyAudits: [
            'network-requests',
        ],
    },
};