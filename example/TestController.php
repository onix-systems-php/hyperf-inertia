<?php
declare(strict_types=1);

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use OnixSystemsPHP\HyperfCore\Controller\AbstractController;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

#[Controller]
class TestController extends AbstractController
{

    public function __construct(
        protected ValidatorFactoryInterface $validationFactory
    ) {
    }
    #[GetMapping(path: '/test-feedback')]
    public function test(RequestInterface $request)
    {
        return inertia('Test', [
            'title' => 'Feedback form',
            'formTitle' => "What's on your mind?"
        ])->toResponse($request);
    }

    #[PostMapping('/test-feedback')]
    public function testFeedback(RequestInterface $request)
    {
        $rules = [
            'fullname' => 'required|string|min:3|max:255',
            'email' => 'required|email',
            'message' => 'required|string|min:10|max:255',
        ];

        $validator = $this->validationFactory->make($request->all(), $rules);
        $validator->validate();

        return redirect_with('test-feedback', [
            'alert' => [
                'message' => 'Feedback was successfully sent',
                'type' => 'success',
            ],
        ]);
    }
}
