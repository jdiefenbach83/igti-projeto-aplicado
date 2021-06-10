const gateway = require('@/api/httpClient');

async function getAll() {
  return await gateway.httpClient().get('/assets');
}

module.exports = {
  getAll,
};