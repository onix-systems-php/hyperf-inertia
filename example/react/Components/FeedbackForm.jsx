import React, { useState } from 'react';
import { useForm } from '@inertiajs/inertia-react';

const FORM_URL = '/test-feedback';

const FeedbackForm = () => {
  const { data, setData, post, errors, reset } = useForm({
    fullname: '',
    email: '',
    message: '',
  });

  const onSubmit = (e) => {
    e.preventDefault();
    post(FORM_URL, {
      preserveState: true,
      onSuccess: () => reset(),
    });
  };

  return (
    <form onSubmit={onSubmit}>
      <div>
        <span className="uppercase text-sm text-gray-600 font-bold">Full Name</span>
        <input
          className="w-full bg-gray-300 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline"
          type="text"
          name="fullname"
          placeholder=""
          value={data.fullname}
          onChange={(e) => setData('fullname', e.target.value)}
        />
        {errors.fullname && <div className="invalid-feedback">{errors.fullname}</div>}
      </div>

      <div className="mt-8">
        <span className="uppercase text-sm text-gray-600 font-bold">Email</span>
        <input
          className="w-full bg-gray-300 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline"
          type="text"
          name="email"
          placeholder=""
          value={data.email}
          onChange={(e) => setData('email', e.target.value)}
        />
        {errors.email && <div className="invalid-feedback">{errors.email}</div>}
      </div>

      <div className="mt-8">
        <span className="uppercase text-sm text-gray-600 font-bold">Message</span>
        <textarea
          className="w-full h-32 bg-gray-300 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline"
          name="message"
          value={data.message}
          onChange={(e) => setData('message', e.target.value)}
        />
        {errors.message && <div className="invalid-feedback">{errors.message}</div>}
      </div>

      <div className="mt-8">
        <button
          className="uppercase text-sm font-bold tracking-wide bg-indigo-500 text-gray-100 p-3 rounded-lg w-full focus:outline-none focus:shadow-outline"
        >
          Send Message
        </button>
      </div>
    </form>
  );
};

export default FeedbackForm;
