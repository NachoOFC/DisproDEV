cd c:\Users\ignac\OneDrive\Escritorio\repositorios\DisproDEV

git add pages/compass/index.vue nuxt.config.ts
git commit -m "fix: Remove broken links and fix prerendering - enable SSR for static generation"
git push origin main// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  ssr: true,
  modules: ['@nuxtjs/tailwindcss'],
  nitro: {
    prerender: {
      crawlLinks: true,
      routes: ['/']
    }
  }
})
