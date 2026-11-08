<script setup>
defineProps({
    label: {
        type: String,
        required: true,
    },
    value: {
        type: [Number, String],
        required: true,
    },
    subtitle: {
        type: String,
        default: '',
    },
    color: {
        type: String,
        default: 'teal',
    },
});

const dotColors = {
    teal: '#0F765E',
    blue: '#3B82F6',
    amber: '#F59E0B',
    rose: '#F43F5E',
};
</script>

<template>
    <div class="card-panel">
        <div class="d-flex align-items-start justify-content-between">
            <div>
                <p class="mb-2" style="font-size: 13px; color: #6B7280;">{{ label }}</p>
                <p class="mb-1 fw-bold" style="font-size: 28px; line-height: 1; color: #111827;">{{ value }}</p>
                <p v-if="subtitle" class="mb-0" style="font-size: 12px; color: #9CA3AF;">{{ subtitle }}</p>
            </div>
            <div
                style="width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0;"
                :style="{ backgroundColor: dotColors[color] || dotColors.teal }"
            />
        </div>
    </div>
</template>
