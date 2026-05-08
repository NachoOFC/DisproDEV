export const useToast = () => {
  const toasts = useState('toasts', () => [])

  const remove = (id) => {
    toasts.value = (toasts.value || []).filter((t) => t.id !== id)
  }

  const show = (message, options = {}) => {
    const id = `${Date.now()}-${Math.random().toString(16).slice(2)}`
    const type = options.type || 'info'
    const title = options.title
    const timeout = Number.isFinite(options.timeout) ? options.timeout : 3000

    const toast = {
      id,
      type,
      title,
      message: String(message ?? ''),
      timeout
    }

    toasts.value = [...(toasts.value || []), toast]

    if (import.meta.client && timeout > 0) {
      window.setTimeout(() => remove(id), timeout)
    }

    return id
  }

  return {
    toasts,
    show,
    success: (message, options = {}) => show(message, { ...options, type: 'success' }),
    error: (message, options = {}) => show(message, { ...options, type: 'error' }),
    warning: (message, options = {}) => show(message, { ...options, type: 'warning' }),
    info: (message, options = {}) => show(message, { ...options, type: 'info' }),
    remove,
    clear: () => {
      toasts.value = []
    }
  }
}
