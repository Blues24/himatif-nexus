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
        validator: (val) => ['teal', 'blue', 'amber', 'rose'].includes(val),
    },
});

const colorClasses = {
    teal: 'bg-teal-500',
    blue: 'bg-blue-500',
    amber: 'bg-amber-500',
    rose: 'bg-rose-500',

};
</script>

<template>
    <div class="rounded-xl border border-gray-200 bg-white p-5">
        <div class="flex items-start justify-between">
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


