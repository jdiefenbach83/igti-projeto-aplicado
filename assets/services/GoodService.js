const gateway = require('@/api/httpClient');

async function getAll() {
  return gateway.httpClient().get('/goods');
}

module.exports = {
  getAll,
};