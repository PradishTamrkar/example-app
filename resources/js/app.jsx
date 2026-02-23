import { createRoot } from 'react-dom/client'
import { createInertiaApp } from '@inertiajs/react'
import { StrictMode } from 'react'
import { InertiaProgress } from '@inertiajs/progress'

import './index.css'

InertiaProgress.init()

createInertiaApp({
  resolve: name => import(`./Pages/${name}`),
  setup({ el, App, props }) {
    createRoot(el).render(
      <StrictMode>
        <App {...props} />
      </StrictMode>
    )
  },
})
