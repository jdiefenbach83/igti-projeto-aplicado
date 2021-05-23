const gateway = require('@/api/httpClient');

async function getAll() {
  const { data } = await gateway.httpClient().get('/consolidations');

  return data;
}

module.exports = {
  getAll,
};