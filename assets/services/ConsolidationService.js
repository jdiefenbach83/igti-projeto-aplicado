const gateway = require('@/api/httpClient');

async function getAll() {
  return gateway.httpClient().get('/consolidations');
}

module.exports = {
  getAll,
};