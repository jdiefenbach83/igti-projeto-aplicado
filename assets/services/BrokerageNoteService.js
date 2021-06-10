const gateway = require('@/api/httpClient');

async function getAll() {
  return await gateway.httpClient().get('/brokerageNotes');
}

async function getById(id) {
  return await gateway.httpClient().get(`/brokerageNotes/${id}`);
}

async function add(brokerageNote) {
  return await gateway.httpClient().post('/brokerageNotes', JSON.stringify(brokerageNote));
}

async function edit(id, brokerageNote) {
  return await gateway.httpClient().put(`/brokerageNotes/${id}`, JSON.stringify(brokerageNote));
}

async function remove(brokerageNote) {
  return await gateway.httpClient().delete(`/brokerageNotes/${brokerageNote.id}`);
}

async function addOperation(operation) {
  return await gateway.httpClient().post(`/brokerageNotes/${operation.brokerage_note_id}/operations`, JSON.stringify(operation));
}

async function editOperation(operation) {
  return await gateway.httpClient().put(`/brokerageNotes/${operation.brokerage_note_id}/operations/${operation.line}`, JSON.stringify(operation));
}

async function removeOperation(operation) {
  return await gateway.httpClient().delete(`/brokerageNotes/${operation.brokerage_note_id}/operations/${operation.line}`);
}

module.exports = {
  getAll,
  getById,
  add,
  edit,
  remove,
  addOperation,
  editOperation,
  removeOperation,
};