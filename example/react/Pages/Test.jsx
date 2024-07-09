import React, { useEffect, useState } from 'react';
import { usePage } from '@inertiajs/inertia-react';
import FeedbackForm from '../Components/FeedbackForm';

const Test = ({ title, formTitle }) => {
  const { props } = usePage();
  const [alert, setAlert] = useState(null);

  useEffect(() => {
    if (props.flash.alert) {
      setAlert(props.flash.alert);
    }
  }, [props.flash.alert]);

  return (
    <>
      {alert && (
        <div className="bg-green-500 text-white p-4 text-center">
          {alert.message}
        </div>
      )}

      <div className="text-center w-full">
        <h1 className="text-4xl lg:text-6xl">{title}</h1>
      </div>

      <div className="max-w-screen-xl mt-24 px-8 grid gap-8 grid-cols-1 md:grid-cols-2 md:px-12 lg:px-16 xl:px-32 py-16 mx-auto bg-gray-100 text-gray-900 rounded-lg shadow-lg">
        <div className="flex flex-col justify-between">
          <div>
            <h2 className="text-4xl lg:text-5xl font-bold leading-tight">{formTitle}</h2>
          </div>
        </div>
        <FeedbackForm />
      </div>
    </>
  );
};

export default Test;
