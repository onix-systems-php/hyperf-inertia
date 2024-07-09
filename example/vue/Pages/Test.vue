<script setup>
  import {computed} from "vue";
  import {usePage} from "@inertiajs/vue3";
  import FeedbackForm  from "../Components/FeedbackForm.vue";
  const page = usePage();

  const alert = computed(() => page.props.flash.alert);
  defineProps({ title: String, formTitle: String });

</script>

<template>
  <div v-if="alert" class="bg-green-500 text-white p-4 text-center">
    {{alert.message}}
  </div>

  <div class="text-center w-full">
    <h1 class="text-4xl lg:text-6xl">{{title}}</h1>
  </div>
  <div
    class="max-w-screen-xl mt-24 px-8 grid gap-8 grid-cols-1 md:grid-cols-2 md:px-12 lg:px-16 xl:px-32 py-16 mx-auto bg-gray-100 text-gray-900 rounded-lg shadow-lg">
    <div class="flex flex-col justify-between">
      <div>
        <h2 class="text-4xl lg:text-5xl font-bold leading-tight">{{formTitle}}</h2>
      </div>
    </div>
    <FeedbackForm />
  </div>
</template>

