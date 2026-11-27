<script setup>
defineProps({
    title: { type: String, default: '' },
    label: { type: String, default: '' },
    value: { type: [Number, String], required: true },
    subtitle: { type: String, default: '' },
    variant: { type: String, default: 'teal' },
    icon: { type: String, default: '' },
    color: { type: String, default: '' },
});

const iconColors = {
    teal: '#0F765E',
    green: '#0F765E',
    blue: '#3B82F6',
    amber: '#F59E0B',
    gray: '#6B7280',
    rose: '#F43F5E',
};
</script>

<template>
    <div class="card-panel">
        <div class="d-flex align-items-start justify-content-between">
            <div>
                <p class="mb-2" style="font-size: 13px; color: #6B7280;">{{ title || label }}</p>
                <p class="mb-1 fw-bold" style="font-size: 28px; line-height: 1; color: #111827;">{{ value }}</p>
                <p v-if="subtitle" class="mb-0" style="font-size: 12px; color: #9CA3AF;">{{ subtitle }}</p>
            </div>
            <div
                style="width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0; margin-top: 4px;"
                :style="{ backgroundColor: iconColors[variant || color] || iconColors.teal }"
            />
        </div>
    </div>
</template>
