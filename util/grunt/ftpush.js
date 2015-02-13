// -------------------------------------
// Grunt ftpush
// -------------------------------------

module.exports = {
  deploy: {
    auth: {
      host: 'embeddedfitness.nl',
      port: 21,
      authKey: 'auth'
    },
    src: 'dist',
    dest: '/httpdocs/wp-content/plugins/bureauopvallend',
    simple: true,
    useList: true
  },


};

