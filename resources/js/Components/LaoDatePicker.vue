<template>
  <flat-pickr
    v-model="modelValueProxy"
    class="form-control"
    :config="config"
    :placeholder="placeholder"
  />
</template>

<script setup>
import { computed } from "vue";
import FlatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import { Lao } from "flatpickr/dist/l10n/la.js"; // ນຳໃຊ້ພາສາລາວ

const props = defineProps({
  modelValue: String,
  placeholder: {
    type: String,
    default: "ເລືອກວັນທີ...",
  },
  enableTime: {
    type: Boolean,
    default: false,
  },
  dateFormat: {
    type: String,
    default: "d/m/Y",
  },
});

const emit = defineEmits(["update:modelValue"]);

const modelValueProxy = computed({
  get: () => props.modelValue,
  set: (val) => emit("update:modelValue", val),
});

const config = {
  locale: Lao, // ຕັ້ງພາສາເປັນລາວ
  dateFormat: props.dateFormat,
  enableTime: props.enableTime,
  time_24hr: true,
};
</script>
