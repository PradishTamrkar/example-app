import { createRoot } from 'react-dom/client'
import { createInertiaApp } from '@inertiajs/react'
import { StrictMode } from 'react'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true })
    return pages[`./Pages/${name}.jsx`]
  },
  setup({ el, App, props }) {
    createRoot(el).render(
      <StrictMode>
        <App {...props} />
      </StrictMode>
    )
  },
})
