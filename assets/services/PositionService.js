const gateway = require('@/api/httpClient');

async function getAll() {
  return gateway.httpClient().get('/positions');
}

module.exports = {
  getAll,
};