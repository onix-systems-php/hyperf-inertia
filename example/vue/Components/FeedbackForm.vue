<script setup>
  import {useForm} from '@inertiajs/vue3';

  const FORM_URL = '/test-feedback';
  let $form = useForm({
    fullname: '',
    email: '',
    message: '',
  });

  function onSubmit() {
    $form.post(FORM_URL, {
      preserveState: true,
      onSuccess: () => {
        $form.reset();
      },
    });
  }
</script>
<template>
  <form @submit.prevent="onSubmit">
    <div>
      <span class="uppercase text-sm text-gray-600 font-bold">Full Name</span>
      <input class="w-full bg-gray-300 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline"
             type="text"
             name="fullname"
             placeholder=""
             v-model="$form.fullname"
      >
      <div v-if="$form.errors.fullname" class="invalid-feedback">{{$form.errors.fullname}}</div>
    </div>
    <div class="mt-8">
      <span class="uppercase text-sm text-gray-600 font-bold">Email</span>
      <input class="w-full bg-gray-300 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline"
             type="text"
             name="email"
             placeholder=""
             v-model="$form.email"
      >
      <div v-if="$form.errors.email" class="invalid-feedback">{{$form.errors.email}}</div>
    </div>
    <div class="mt-8">
      <span class="uppercase text-sm text-gray-600 font-bold">Message</span>
      <textarea
        class="w-full h-32 bg-gray-300 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline"
        name="message"
        v-model="$form.message"
      />
      <div v-if=" $form.errors.message" class="invalid-feedback">{{$form.errors.message}}</div>
    </div>
    <div class="mt-8">
      <button
        class="uppercase text-sm font-bold tracking-wide bg-indigo-500 text-gray-100 p-3 rounded-lg w-full focus:outline-none focus:shadow-outline">
        Send Message
      </button>
    </div>
  </form>
</template>
