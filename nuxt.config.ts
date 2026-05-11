
// https://nuxt.com/docs/api/configuration/nuxt-config
import { defineNuxtConfig } from 'nuxt/config'

export default defineNuxtConfig({
  compatibilityDate: '2025-11-02',
  ssr: true,
  modules: ['@nuxtjs/tailwindcss'],
  // @ts-ignore Nuxt runtime supports `nitro`, but Nuxt 3.21.2 schema types currently mark it as `never`.
  nitro: {
    preset: 'netlify'
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
        { rel: 'icon', type: 'image/svg+xml', href: '/favicon.svg?v=alogis-2025' },
        { rel: 'apple-touch-icon', href: '/favicon.svg?v=alogis-2025' },
        {
          rel: 'stylesheet',
          href: 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css'
        }
      ]
    }
  }
})

