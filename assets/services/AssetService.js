const gateway = require('@/api/httpClient');

async function getAll() {
  const { data } = await gateway.httpClient().get('/assets');

  return data;
}

module.exports = {
  getAll,
};