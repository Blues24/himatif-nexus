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
                <p class="text-sm text-gray-500">{{ label }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ value }}</p>
                <p v-if="subtitle" class="mt-1 text-xs text-gray-400">{{ subtitle }}</p>
            </div>
            <div :class="[colorClasses[color], 'h-3 w-3 rounded-full']" />
        </div>
    </div>
</template>

