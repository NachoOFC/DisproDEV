import jsPDF from 'jspdf'

export const usePdfGenerator = () => {
  // Generar PDF para Facturas
  const generarPdfFactura = (factura) => {
    const doc = new jsPDF()
    
    // Encabezado
    doc.setFillColor(3, 155, 229)
    doc.rect(0, 0, 210, 30, 'F')
    doc.setTextColor(255, 255, 255)
    doc.setFontSize(24)
    doc.text('ALOGIS', 20, 20)
    
    // Título
    doc.setTextColor(0, 0, 0)
    doc.setFontSize(18)
    doc.text(`FACTURA ELECTRÓNICA`, 20, 50)
    
    // Información de la factura
    doc.setFontSize(10)
    doc.text(`Folio: ${factura.folio}`, 20, 65)
    doc.text(`Cliente: ${factura.cliente}`, 20, 73)
    doc.text(`Fecha: ${factura.fecha}`, 20, 81)
    doc.text(`Estado: ${factura.estado}`, 20, 89)
    
    // Detalles
    doc.setFontSize(12)
    doc.text('Detalles de la Factura', 20, 110)
    
    // Tabla
    let yPosition = 125
    doc.setFontSize(10)
    doc.setTextColor(80, 80, 80)
    doc.text('Descripción', 20, yPosition)
    doc.text('Cantidad', 100, yPosition)
    doc.text('V. Unitario', 130, yPosition)
    doc.text('Total', 160, yPosition)
    
    yPosition += 10
    doc.line(20, yPosition - 3, 190, yPosition - 3)
    
    // Items de ejemplo
    const items = [
      { desc: 'Productos Distribuidos', cant: 100, unitario: 5000 },
      { desc: 'Servicios de Logística', cant: 1, unitario: 100000 }
    ]
    
    items.forEach(item => {
      const total = item.cant * item.unitario
      doc.text(item.desc, 20, yPosition)
      doc.text(`${item.cant}`, 105, yPosition)
      doc.text(`$${item.unitario}`, 135, yPosition)
      doc.text(`$${total}`, 165, yPosition)
      yPosition += 10
    })
    
    yPosition += 5
    doc.line(20, yPosition, 190, yPosition)
    yPosition += 10
    
    // Totales
    const subtotal = items.reduce((sum, i) => sum + (i.cant * i.unitario), 0)
    const iva = subtotal * 0.19
    const total = subtotal + iva
    
    doc.setFontSize(11)
    doc.text(`Subtotal: $${subtotal}`, 130, yPosition)
    yPosition += 8
    doc.text(`IVA (19%): $${iva.toFixed(0)}`, 130, yPosition)
    yPosition += 8
    doc.setFontSize(12)
    doc.setFont(undefined, 'bold')
    doc.text(`TOTAL: $${total.toFixed(0)}`, 130, yPosition)
    
    // Pie de página
    doc.setFont(undefined, 'normal')
    doc.setFontSize(8)
    doc.setTextColor(150, 150, 150)
    doc.text('© 2024 ALOGIS - Sistema de Gestión de Distribución', 20, 280)
    doc.text('Powered by Nuxt 3 + Vue 3', 20, 285)
    
    // Descargar
    doc.save(`Factura_${factura.folio}.pdf`)
  }

  // Generar PDF para Guías de Despacho
  const generarPdfGuia = (guia, formatDate) => {
    const doc = new jsPDF()
    
    // Encabezado
    doc.setFillColor(3, 155, 229)
    doc.rect(0, 0, 210, 30, 'F')
    doc.setTextColor(255, 255, 255)
    doc.setFontSize(24)
    doc.text('ALOGIS', 20, 20)
    
    // Título
    doc.setTextColor(0, 0, 0)
    doc.setFontSize(18)
    doc.text(`GUÍA DE DESPACHO`, 20, 50)
    
    // Información principal
    doc.setFontSize(10)
    doc.text(`Número: ${guia.numero}`, 20, 65)
    doc.text(`Orden Compra: ${guia.ordenCompra}`, 20, 73)
    doc.text(`Cliente: ${guia.cliente}`, 20, 81)
    doc.text(`Transportista: ${guia.transportista}`, 20, 89)
    
    // Detalles
    doc.setFontSize(12)
    doc.text('Detalles del Envío', 20, 110)
    
    doc.setFontSize(10)
    doc.setTextColor(80, 80, 80)
    let yPosition = 130
    doc.text(`Patente: ${guia.patente}`, 20, yPosition)
    yPosition += 8
    doc.text(`Cantidad de Bidones: ${guia.cantidadBidones}`, 20, yPosition)
    yPosition += 8
    doc.text(`Fecha Despacho: ${formatDate(guia.fechaDespacho)}`, 20, yPosition)
    yPosition += 8
    doc.text(`Fecha Entrega Estimada: ${formatDate(guia.fechaEntregaEstimada)}`, 20, yPosition)
    yPosition += 8
    doc.text(`Estado: ${guia.estado}`, 20, yPosition)
    
    // Observaciones
    yPosition += 15
    doc.setFontSize(11)
    doc.text('Observaciones:', 20, yPosition)
    yPosition += 8
    doc.setFontSize(10)
    doc.text(guia.observaciones || 'Sin observaciones', 20, yPosition)
    
    // Pie de página
    doc.setFont(undefined, 'normal')
    doc.setFontSize(8)
    doc.setTextColor(150, 150, 150)
    doc.text('© 2024 ALOGIS - Sistema de Gestión de Distribución', 20, 280)
    doc.text('Powered by Nuxt 3 + Vue 3', 20, 285)
    
    // Descargar
    doc.save(`Guia_${guia.numero}.pdf`)
  }

  // Generar PDF para Reportes generales
  const generarPdfReporte = (reporte) => {
    const doc = new jsPDF()
    
    // Encabezado
    doc.setFillColor(3, 155, 229)
    doc.rect(0, 0, 210, 30, 'F')
    doc.setTextColor(255, 255, 255)
    doc.setFontSize(24)
    doc.text('ALOGIS', 20, 20)
    
    // Título
    doc.setTextColor(0, 0, 0)
    doc.setFontSize(18)
    doc.text(reporte.titulo, 20, 50)
    
    // Período
    doc.setFontSize(10)
    doc.setTextColor(100, 100, 100)
    doc.text(`Período: ${reporte.periodo}`, 20, 60)
    doc.text(`Generado: ${new Date().toLocaleDateString('es-ES')} ${new Date().toLocaleTimeString('es-ES')}`, 20, 68)
    
    // Línea separadora
    doc.setDrawColor(200, 200, 200)
    doc.line(20, 75, 190, 75)
    
    // Contenido
    let yPosition = 90
    doc.setFontSize(11)
    doc.setTextColor(0, 0, 0)
    
    reporte.datos.forEach((dato) => {
      doc.setFont(undefined, 'bold')
      doc.text(`${dato.label}:`, 20, yPosition)
      doc.setFont(undefined, 'normal')
      doc.text(dato.valor, 100, yPosition)
      yPosition += 10
    })
    
    // Información adicional
    yPosition += 15
    doc.setFontSize(10)
    doc.setTextColor(100, 100, 100)
    doc.text('Este reporte fue generado automáticamente por el sistema ALOGIS.', 20, yPosition)
    yPosition += 8
    doc.text('Para más información, contacte a soporte@dispro.com', 20, yPosition)
    
    // Pie de página
    doc.setFont(undefined, 'normal')
    doc.setFontSize(8)
    doc.setTextColor(150, 150, 150)
    doc.text('© 2024 ALOGIS - Sistema de Gestión de Distribución', 20, 280)
    doc.text('Powered by Nuxt 3 + Vue 3', 20, 285)
    
    // Descargar
    const nombreArchivo = `${reporte.tipo}_${new Date().getTime()}.pdf`
    doc.save(nombreArchivo)
  }

  return {
    generarPdfFactura,
    generarPdfGuia,
    generarPdfReporte
  }
}
