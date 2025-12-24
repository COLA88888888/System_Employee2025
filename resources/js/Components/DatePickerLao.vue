<template>
  <div class="date-picker-wrapper">
    <div class="form-group">

      <label v-if="label" :for="inputId" class="form-label">
        <b>{{ label }}<span v-if="required" class="text-danger"> *</span></b>
      </label>

      <flat-pickr
        :id="inputId"
        v-model="selectedDate"
        :config="config"
        :placeholder="placeholder"
        class="form-control"
        @on-change="onDateChange"
      />
    </div>
  </div>
</template>


<script>
import VueFlatPickr from "vue-flatpickr-component";
import { Lao } from "../locales/lao";
import monthSelectPlugin from "flatpickr/dist/plugins/monthSelect/index.js";
import "flatpickr/dist/plugins/monthSelect/style.css";

export default {
  components: { "flat-pickr": VueFlatPickr },

  props: {
    modelValue: [String, Date],
    label: String,

    /** 👇 ນີ້ສຳຄັນ */
    monthPicker: {
      type: Boolean,
      default: false
    },

    placeholder: {
      type: String,
      default: "ເລືອກວັນເດືອນປີ...",
    },

    required: Boolean,
    inputId: String,

    mode: {
      type: String,
      default: "single", // single, month, year
    },

    dateFormat: {
      type: String,
      default: "Y-m-d",
    },

    yearPicker: {
      type: Boolean,
      default: false
    }
  },

  emits: ["update:modelValue"],

  data() {
    let initialDate = this.convertToDate(this.modelValue);

    const cfg = {
      locale: Lao,
      altInput: true,
      allowInput: false,
      time_24hr: true,

      /** ✔ ຖ້າ monthPicker = true → ໂໝດເລືອກເດືອນ */
      dateFormat: this.monthPicker
        ? "Y-m"
        : this.yearPicker
        ? "Y"
        : "Y-m-d",

      dateFormat:
        this.mode === "month" ? "Y-m" :
        this.mode === "year" ? "Y" :
        this.dateFormat,

      altInput: true,
      altFormat:
        this.mode === "month" ? "F Y" :
        this.mode === "year" ? "Y" :
        "d/m/Y",

      plugins: this.monthPicker
        ? [new monthSelectPlugin({ shorthand: false })]
        : [],
    };

    return {
      selectedDate: initialDate,
      config: cfg,
    };
  },

  watch: {
    modelValue(v) {
      this.selectedDate = this.convertToDate(v);
    }
  },

  methods: {
    convertToDate(val) {
      if (!val) return null;

      if (this.monthPicker && val.length === 7) {
        const [y, m] = val.split("-");
        return new Date(Number(y), Number(m) - 1, 1);
      }

      if (this.yearPicker && val.length === 4) {
        return new Date(Number(val), 0, 1);
      }

      return new Date(val);
    },

    onDateChange(selected) {
      if (!selected.length) return;

      const date = selected[0];

      if (this.monthPicker) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, "0");
        this.$emit("update:modelValue", `${y}-${m}`);
        return;
      }

      if (this.yearPicker) {
        this.$emit("update:modelValue", String(date.getFullYear()));
        return;
      }

      // normal date
      const y = date.getFullYear();
      const m = String(date.getMonth() + 1).padStart(2, "0");
      const d = String(date.getDate()).padStart(2, "0");
      this.$emit("update:modelValue", `${y}-${m}-${d}`);
    },
  },
};
</script>
