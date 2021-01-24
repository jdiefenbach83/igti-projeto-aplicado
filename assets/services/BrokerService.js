const gateway = require('@/api/httpClient');

async function getAll() {
  const { data } = await gateway.httpClient().get('/brokers');

  return data;
}

module.exports = {
  getAll,
};