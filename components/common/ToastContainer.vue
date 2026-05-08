<template>
  <div class="fixed top-4 right-4 z-50 w-full max-w-sm space-y-3 pointer-events-none">
    <TransitionGroup name="toast" tag="div" class="space-y-3">
      <div
        v-for="t in toasts"
        :key="t.id"
        class="pointer-events-auto overflow-hidden rounded-lg border-l-4 bg-white shadow-xl"
        role="status"
        aria-live="polite"
        :class="barClass(t.type)"
      >
        <div class="flex items-stretch">
          <div class="flex-1 px-5 py-4">
            <div class="flex items-start gap-3">
              <div :class="['mt-0.5 text-lg shrink-0', iconClass(t.type)]">
                <i :class="iconName(t.type)" aria-hidden="true"></i>
              </div>

              <div class="flex-1">
                <div v-if="t.title" class="text-sm font-bold text-slate-900 mb-1">
                  {{ t.title }}
                </div>
                <div class="text-sm text-slate-600">
                  {{ t.message }}
                </div>
              </div>

              <button
                type="button"
                class="ml-3 inline-flex h-6 w-6 items-center justify-center rounded text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors"
                aria-label="Cerrar notificación"
                @click="toast.remove(t.id)"
              >
                <i class="fas fa-times" aria-hidden="true"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup>
const toast = useToast()
const toasts = computed(() => toast.toasts.value || [])

const barClass = (type) => {
  switch (type) {
    case 'success':
      return 'border-l-green-500 bg-green-50'
    case 'error':
      return 'border-l-red-500 bg-red-50'
    case 'warning':
      return 'border-l-amber-500 bg-amber-50'
    default:
      return 'border-l-blue-500 bg-blue-50'
  }
}

const iconClass = (type) => {
  switch (type) {
    case 'success':
      return 'text-green-500'
    case 'error':
      return 'text-red-500'
    case 'warning':
      return 'text-amber-500'
    default:
      return 'text-blue-500'
  }
}

const iconName = (type) => {
  switch (type) {
    case 'success':
      return 'fas fa-check-circle'
    case 'error':
      return 'fas fa-times-circle'
    case 'warning':
      return 'fas fa-exclamation-triangle'
    default:
      return 'fas fa-info-circle'
  }
}
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 250ms cubic-bezier(0.4, 0, 0.2, 1);
}

.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateX(384px);
}
</style>
