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
<<<<<<< HEAD
    },
});

const dotColors = {
    teal: '#0F765E',
    blue: '#3B82F6',
    amber: '#F59E0B',
    rose: '#F43F5E',
=======
        validator: (val) => ['teal', 'blue', 'amber', 'rose'].includes(val),
    },
});

const colorClasses = {
    teal: 'bg-teal-500',
    blue: 'bg-blue-500',
    amber: 'bg-amber-500',
    rose: 'bg-rose-500',
>>>>>>> 608554c4545df0bcc674d83bb0c07fe78b0f3759
};
</script>

<template>
<<<<<<< HEAD
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
=======
    <div class="rounded-xl border border-gray-200 bg-white p-5">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500">{{ label }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ value }}</p>
                <p v-if="subtitle" class="mt-1 text-xs text-gray-400">{{ subtitle }}</p>
            </div>
            <div :class="[colorClasses[color], 'h-3 w-3 rounded-full']" />
        </div>
    </div>
</template>

>>>>>>> 608554c4545df0bcc674d83bb0c07fe78b0f3759
