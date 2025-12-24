<template>
  <div>
    <label v-if="label" :for="inputId" class="form-label fw-bold">{{ label }}<span v-if="required" class="text-danger">*</span></label>
    <flat-pickr
      :config="config"
      v-model="internalValue"
      :id="inputId"
      class="form-control"
      :placeholder="placeholder"
    ></flat-pickr>
  </div>
</template>

<script>
import FlatPickr from 'vue-flatpickr-component';
export default {
  name: 'TimePickerLao',
  components: { 'flat-pickr': FlatPickr },
  props: {
    modelValue: [String, Date],
    label: { type: String, default: '' },
    placeholder: { type: String, default: '' },
    required: { type: Boolean, default: false },
    inputId: { type: String, default: '' },
    enableSeconds: { type: Boolean, default: false },
    time_24hr: { type: Boolean, default: true },
    dateFormat: { type: String, default: 'H:i' }
  },
  emits: ['update:modelValue'],
  computed: {
    config() {
      return {
        enableTime: true,
        noCalendar: true,
        time_24hr: this.time_24hr,
        enableSeconds: this.enableSeconds,
        dateFormat: this.dateFormat,
      };
    },
    internalValue: {
      get() {
        return this.modelValue;
      },
      set(val) {
        this.$emit('update:modelValue', val);
      }
    }
  }
};
</script>

<style scoped>
.form-label { display:block; margin-bottom:0.25rem; }
.form-control { width:100%; padding:0.5rem; border:1px solid #d1d5db; border-radius:0.375rem }
</style>
