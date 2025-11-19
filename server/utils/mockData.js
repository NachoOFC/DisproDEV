export const mockData = {
  productos: [
    { id: 1, nombre: 'Agua Filtrada 5L', sku: 'PROD-001', precio: 2500, stock: 150 },
    { id: 2, nombre: 'Agua Destilada 5L', sku: 'PROD-002', precio: 3000, stock: 89 },
    { id: 3, nombre: 'Agua Purificada 10L', sku: 'PROD-003', precio: 5000, stock: 45 }
  ],
  facturas: [
    { id: 1, folio: 'F-2025-001', cliente: 'Centro Santiago', fecha: '2025-11-01', estado: 'emitida', monto: 1250000 },
    { id: 2, folio: 'F-2025-002', cliente: 'Empresa A', fecha: '2025-11-02', estado: 'anulada', monto: 450000 }
  ],
  usuarios: [
    { id: 1, nombre: 'Juan Pérez', email: 'juan@dispro.com', rol: 'bodeguero', estado: 'activo' },
    { id: 2, nombre: 'María García', email: 'maria@dispro.com', rol: 'admin', estado: 'activo' }
  ]
}

let nextIds = {
  productos: mockData.productos.length + 1,
  facturas: mockData.facturas.length + 1,
  usuarios: mockData.usuarios.length + 1
}

export const list = (collection) => {
  return mockData[collection] || []
}

export const findById = (collection, id) => {
  const listCol = list(collection)
  return listCol.find(item => Number(item.id) === Number(id))
}

export const create = (collection, payload) => {
  const id = nextIds[collection]++
  const item = { id, ...payload }
  if (!mockData[collection]) mockData[collection] = []
  mockData[collection].push(item)
  return item
}
