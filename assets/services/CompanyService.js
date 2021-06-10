const gateway = require('@/api/httpClient');

async function getAll() {
  return gateway.httpClient().get('/companies');
}

module.exports = {
  getAll,
};