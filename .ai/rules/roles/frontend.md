---
trigger: "@frontend"
keywords: ["vue", "component", "tailwind", "css", "ui", "layout", "page", "form", "modal", "button", "input"]
paths:
  - "resources/js/**"
  - "resources/css/**"
---

# @frontend — Frontend Team Role

## Scope

Frontend team owns semua Vue components, pages, layouts, dan styling dengan Tailwind CSS.

### Files Owned

| Folder | Purpose |
|---|---|
| `resources/js/Pages/` | Inertia page components (per module) |
| `resources/js/Components/` | Reusable UI components |
| `resources/js/Layouts/` | Page layout shells |
| `resources/js/Builder/` | Puck page builder (planned) |
| `resources/css/app.css` | Tailwind CSS + custom styles |

### Team Boundaries

| Path | Frontend | Backend |
|---|---|---|
| `resources/js/Pages/` | **Full Access** | Read Only |
| `resources/js/Components/` | **Full Access** | Read Only |
| `resources/js/Layouts/` | **Full Access** | Read Only |
| `resources/css/` | **Full Access** | Read Only |
| `resources/views/app.blade.php` | Read Only | **Full Access** |

## Conventions

### Vue 3 Composition API

```vue
<!-- ✅ Correct — <script setup> syntax -->
<script setup lang="ts">
import { ref, computed } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps<{
  products: Array<Product>
  filters: { q: string }
}>()

const emit = defineEmits<{
  (e: 'update', id: number): void
}>()
</script>

<template>
  <div>
    <!-- Template content -->
  </div>
</template>
```

### Inertia Integration

```vue
<!-- Using Link component -->
<Link :href="route('produk.show', product.produk_id)">
  {{ product.nama_produk }}
</Link>

<!-- Using useForm -->
<script setup>
const form = useForm({
  nama_produk: '',
  harga_produk: null,
  stok_produk: 0,
})

function submit() {
  form.post(route('produk.store'))
}
</script>
```

### Deferred Props Handling

```vue
<!-- Props yang deferred (fn () =>) akan di-resolve otomatis -->
<script setup>
const props = defineProps<{
  products: Array<Product> // Already resolved by Inertia
}>()
</script>
```

### Tailwind CSS

```vue
<!-- ✅ Correct — utility-first -->
<button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
  Submit
</button>

<!-- ✅ With Tailwind Forms plugin -->
<input type="email" class="rounded-md shadow-sm border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
```

### Component Single Root Element

```vue
<!-- ✅ Correct — single root -->
<template>
  <div>
    <h1>Title</h1>
    <p>Content</p>
  </div>
</template>

<!-- ❌ Wrong — multiple roots -->
<template>
  <h1>Title</h1>
  <p>Content</p>
</template>
```

## Page Structure

### Module Pages

```
resources/js/Pages/
├── Auth/              # Breeze auth pages
│   ├── Login.vue
│   ├── Register.vue
│   └── ...
├── Profile/
│   ├── Edit.vue
│   └── Partials/
├── Shop/              # Danus Shop module
│   ├── Index.vue
│   ├── Show.vue
│   ├── Create.vue
│   └── Edit.vue
├── Blog/              # Infokom module
│   ├── Index.vue
│   ├── Show.vue
│   ├── Create.vue
│   └── Edit.vue
├── Inventory/         # Portal module
│   ├── Index.vue
│   ├── Create.vue
│   ├── Edit.vue
│   ├── Loans.vue
│   └── LoanCreate.vue
├── Admin/             # BPH management (planned)
├── Dashboard.vue
└── Welcome.vue
```

## Common Components

### Breeze Components (15 built-in)

- `ApplicationLogo.vue`
- `Modal.vue`
- `Dropdown.vue`, `DropdownLink.vue`
- `PrimaryButton.vue`, `SecondaryButton.vue`, `DangerButton.vue`
- `TextInput.vue`, `InputLabel.vue`, `InputError.vue`
- `Checkbox.vue`
- `NavLink.vue`, `ResponsiveNavLink.vue`

### Custom Components (Planned)

```
resources/js/Components/
├── ProductCard.vue       # Shop product display
├── ArticleCard.vue       # Blog article preview
├── InventoryTable.vue    # Inventory listing
├── LoanStatusBadge.vue   # Status indicator
├── SearchBar.vue         # Search input with debounce
└── Pagination.vue        # Pagination controls
```

## Puck Builder (Planned)

```javascript
// resources/js/Builder/puckConfig.js
import BannerBlock from './Components/BannerBlock.vue'
import CardLomba from './Components/CardLomba.vue'
import GridBlock from './Components/GridBlock.vue'

export default {
  blocks: {
    BannerBlock,
    CardLomba,
    GridBlock,
  },
}
```

```vue
<!-- BannerBlock.vue -->
<template>
  <div class="banner" :style="{ background: props.backgroundColor }">
    <h2>{{ props.title }}</h2>
    <p>{{ props.description }}</p>
  </div>
</template>

<script setup>
const props = defineProps({
  title: String,
  description: String,
  backgroundColor: String,
})
</script>
```

## Styling Guidelines

### Responsive Design

```vue
<!-- Mobile-first approach -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
  <!-- Cards -->
</div>
```

### Dark Mode

```vue
<div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
  Content
</div>
```

### Focus States

```vue
<button class="focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
  Submit
</button>
```

## Related Roles

- `@backend` — Inertia props dari controller
- `@shop` — Shop pages (Index, Show, Create, Edit)
- `@infokom` — Blog pages
- `@portal` — Inventory pages