const gateway = require('@/api/httpClient');

async function getAll() {
  return await gateway.httpClient().get('/brokers');
}

module.exports = {
  getAll,
};