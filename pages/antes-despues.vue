<script setup>
const oldStack = [
  { name: 'PHP', icon: 'https://cdn.simpleicons.org/php/777BB4' },
  { name: 'MariaDB', icon: 'https://cdn.simpleicons.org/mariadb/FFFFFF' },
  { name: 'HTML', icon: 'https://cdn.simpleicons.org/html5/E34F26' },
  { name: 'CSS Puro', icon: 'https://cdn.simpleicons.org/css/1572B6' },
  { name: 'JS Vanilla', icon: 'https://cdn.simpleicons.org/javascript/F7DF1E' },
  { name: 'Monolito', icon: 'https://cdn.simpleicons.org/apache/EA4335' }
]

const newStack = [
  { name: 'Nuxt 3', icon: 'https://cdn.simpleicons.org/nuxt/00DC82' },
  { name: 'Vue 3', icon: 'https://cdn.simpleicons.org/vuedotjs/4FC08D' },
  { name: 'TailwindCSS', icon: 'https://cdn.simpleicons.org/tailwindcss/06B6D4' },
  { name: 'PostgreSQL', icon: 'https://cdn.simpleicons.org/postgresql/4169E1' },
  { name: 'Neon', icon: 'https://cdn.simpleicons.org/neovim/00E599' },
  { name: 'Netlify', icon: 'https://cdn.simpleicons.org/netlify/00C7B7' },
  { name: 'GitHub', icon: 'https://cdn.simpleicons.org/github/FFFFFF' }
]

const upgrades = [
  { from: 'PHP monolitico', to: 'Nuxt 3 + Nitro Server API' },
  { from: 'MariaDB', to: 'PostgreSQL en Neon' },
  { from: 'CSS manual', to: 'TailwindCSS utilitario' },
  { from: 'UI separada del backend', to: 'Frontend + backend unificados' }
]
</script>

<template>
  <main class="page">
    <section class="hero reveal">
      <p class="pill">Transformacion del Sistema</p>
      <h1>Antes vs Despues</h1>
      <p>
        Migracion desde stack clasico a arquitectura moderna para desarrollo mas rapido,
        mantenimiento simple y despliegue mas limpio.
      </p>
    </section>

    <section class="grid">
      <article class="panel old reveal delay-1">
        <header>
          <h2>ANTES</h2>
          <span class="tag old-tag">Legacy</span>
        </header>
        <p class="lead">PHP + MariaDB + CSS puro</p>
        <div class="chips">
          <div v-for="tech in oldStack" :key="tech.name" class="chip">
            <img :src="tech.icon" :alt="tech.name" loading="lazy" />
            <span>{{ tech.name }}</span>
          </div>
        </div>
      </article>

      <div class="mid-arrow reveal delay-2" aria-hidden="true">→</div>

      <article class="panel modern reveal delay-3">
        <header>
          <h2>DESPUES</h2>
          <span class="tag modern-tag">Actual</span>
        </header>
        <p class="lead">Nuxt 3 + Vue + Tailwind + PostgreSQL (Neon)</p>
        <div class="chips">
          <div v-for="tech in newStack" :key="tech.name" class="chip">
            <img :src="tech.icon" :alt="tech.name" loading="lazy" />
            <span>{{ tech.name }}</span>
          </div>
        </div>
      </article>
    </section>

    <section class="upgrades reveal delay-4">
      <h3>Migraciones Clave</h3>
      <div class="rows">
        <div v-for="item in upgrades" :key="item.from" class="row">
          <span class="from">{{ item.from }}</span>
          <span class="row-arrow">→</span>
          <span class="to">{{ item.to }}</span>
        </div>
      </div>
    </section>

    <section class="footer-note reveal delay-4">
      <p>
        Frase para PPT: Pasamos de una base legacy con PHP + MariaDB a un stack moderno con Nuxt 3,
        API server-side en Nitro y PostgreSQL en Neon, incorporando Vue y TailwindCSS para agilizar el desarrollo.
      </p>
    </section>
  </main>
</template>

<style scoped>
.page {
  --bg-1: #081126;
  --bg-2: #0e1c3f;
  --surface: rgba(11, 26, 59, 0.68);
  --surface-2: rgba(17, 37, 74, 0.84);
  --line: rgba(147, 197, 253, 0.23);
  --text: #eff6ff;
  --muted: #bfdbfe;
  --old: #f59e0b;
  --modern: #22c55e;

  min-height: 100vh;
  padding: 42px 20px 64px;
  color: var(--text);
  font-family: "Trebuchet MS", "Segoe UI", sans-serif;
  background:
    radial-gradient(circle at 18% -5%, rgba(56, 189, 248, 0.27), transparent 36%),
    radial-gradient(circle at 88% 4%, rgba(34, 197, 94, 0.23), transparent 34%),
    linear-gradient(145deg, var(--bg-1), var(--bg-2));
}

.hero {
  max-width: 980px;
  margin: 0 auto 22px;
  text-align: center;
}

.pill {
  display: inline-block;
  margin-bottom: 10px;
  padding: 5px 12px;
  border-radius: 999px;
  border: 1px solid rgba(125, 211, 252, 0.45);
  background: rgba(14, 116, 144, 0.35);
  color: #bae6fd;
  font-weight: 700;
  font-size: 0.76rem;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

h1 {
  margin: 0;
  font-size: clamp(2rem, 4.6vw, 3rem);
  line-height: 1.15;
}

.hero p {
  margin: 12px auto 0;
  max-width: 820px;
  color: var(--muted);
}

.grid {
  max-width: 1120px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  gap: 16px;
  align-items: center;
}

.panel {
  border: 1px solid var(--line);
  border-radius: 24px;
  padding: 20px;
  background: var(--surface);
  box-shadow: 0 16px 34px rgba(2, 6, 23, 0.35);
  backdrop-filter: blur(4px);
}

.panel header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
}

h2 {
  margin: 0;
  font-size: 1.32rem;
}

.lead {
  margin: 8px 0 14px;
  color: #dbeafe;
  font-weight: 700;
}

.tag {
  padding: 4px 9px;
  border-radius: 999px;
  font-size: 0.72rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.08em;
}

.old-tag {
  color: #ffedd5;
  border: 1px solid rgba(251, 146, 60, 0.46);
  background: rgba(124, 45, 18, 0.55);
}

.modern-tag {
  color: #dcfce7;
  border: 1px solid rgba(74, 222, 128, 0.46);
  background: rgba(20, 83, 45, 0.55);
}

.chips {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.chip {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 11px;
  border-radius: 999px;
  border: 1px solid rgba(148, 163, 184, 0.3);
  background: var(--surface-2);
}

.chip img {
  width: 18px;
  height: 18px;
  object-fit: contain;
}

.chip span {
  font-size: 0.92rem;
  font-weight: 700;
}

.mid-arrow {
  font-size: clamp(1.8rem, 3vw, 2.35rem);
  color: #7dd3fc;
  text-shadow: 0 0 18px rgba(125, 211, 252, 0.55);
}

.upgrades {
  max-width: 1120px;
  margin: 22px auto 0;
  padding: 18px;
  border-radius: 18px;
  border: 1px solid var(--line);
  background: rgba(15, 31, 63, 0.72);
}

h3 {
  margin: 0 0 12px;
  font-size: 1.1rem;
}

.rows {
  display: grid;
  grid-template-columns: 1fr;
  gap: 9px;
}

.row {
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  gap: 10px;
  align-items: center;
  padding: 10px;
  border-radius: 12px;
  background: rgba(30, 58, 138, 0.22);
  border: 1px solid rgba(147, 197, 253, 0.2);
}

.from,
.to {
  font-weight: 700;
}

.from {
  color: #fed7aa;
}

.to {
  color: #bbf7d0;
}

.row-arrow {
  color: #93c5fd;
  font-weight: 900;
}

.footer-note {
  max-width: 980px;
  margin: 14px auto 0;
  color: #cfe2ff;
  text-align: center;
}

.reveal {
  opacity: 0;
  transform: translateY(12px);
  animation: reveal 0.65s ease forwards;
}

.delay-1 {
  animation-delay: 0.12s;
}

.delay-2 {
  animation-delay: 0.22s;
}

.delay-3 {
  animation-delay: 0.32s;
}

.delay-4 {
  animation-delay: 0.44s;
}

@keyframes reveal {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 1024px) {
  .grid {
    grid-template-columns: 1fr;
  }

  .mid-arrow {
    transform: rotate(90deg);
    justify-self: center;
  }
}

@media (max-width: 640px) {
  .page {
    padding: 26px 14px 42px;
  }

  .row {
    grid-template-columns: 1fr;
    text-align: center;
  }
}
</style>
