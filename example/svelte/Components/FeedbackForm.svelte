<script>
  import { useForm } from "@inertiajs/svelte";

  const FORM_URL = '/test-feedback';
  let form = useForm({
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
<form on:submit|preventDefault={onSubmit}>
  <div>
    <span class="uppercase text-sm text-gray-600 font-bold">Full Name</span>
    <input class="w-full bg-gray-300 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline"
           type="text"
           name="fullname"
           placeholder=""
           bind:value={$form.fullname}
    >
    {#if $form.errors.fullname}
      <div class="invalid-feedback">{ $form.errors.fullname }</div>
    {/if}
  </div>
  <div class="mt-8">
    <span class="uppercase text-sm text-gray-600 font-bold">Email</span>
    <input class="w-full bg-gray-300 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline"
           type="text"
           name="email"
           placeholder=""
           bind:value={$form.email}
    >
    {#if $form.errors.email}
      <div class="invalid-feedback">{ $form.errors.email }</div>
    {/if}
  </div>
  <div class="mt-8">
    <span class="uppercase text-sm text-gray-600 font-bold">Message</span>
    <textarea
      class="w-full h-32 bg-gray-300 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline"
      name="message"
      bind:value={$form.message}
    />
    {#if $form.errors.message}
      <div class="invalid-feedback">{ $form.errors.message }</div>
    {/if}
  </div>
  <div class="mt-8">
    <button
      class="uppercase text-sm font-bold tracking-wide bg-indigo-500 text-gray-100 p-3 rounded-lg w-full focus:outline-none focus:shadow-outline">
      Send Message
    </button>
  </div>
</form>
