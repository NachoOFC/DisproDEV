
// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-11-02',
  ssr: true,
  modules: ['@nuxtjs/tailwindcss'],
  nitro: {
    prerender: {
      crawlLinks: true,
      routes: ['/']
    }
  },
  app: {
    head: {
      title: 'ALOGIS - DisproDEV',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: 'Sistema de Gestión de Distribución y Abastecimiento' }
      ],
      link: [
        { rel: 'icon', type: 'image/svg+xml', href: '/favicon.svg' }
      ]
    }
  }
})
function defineNuxtConfig(arg0: { compatibilityDate: string; ssr: boolean; modules: string[]; nitro: { prerender: { crawlLinks: boolean; routes: string[]; }; }; app: { head: { title: string; meta: ({ charset: string; } | { name: string; content: string; })[]; link: { rel: string; type: string; href: string; }[]; }; }; }) {
  throw new Error("Function not implemented.");
}

