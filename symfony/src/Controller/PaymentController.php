<?php

namespace App\Controller;

use App\Entity\Membership;
use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * READ - список усіх оплат
     *
     * @return JsonResponse
     */
    #[Route('/payments', name: 'get_payments', methods: [Request::METHOD_GET])]
    public function getPayments(): JsonResponse
    {
        $payments = $this->entityManager->getRepository(Payment::class)->findAll();

        return $this->json(['data' => $payments], Response::HTTP_OK);
    }

    /**
     * READ - одна оплата за id
     *
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/payments/{id}', name: 'get_payment_item', methods: [Request::METHOD_GET])]
    public function getPaymentItem(string $id): JsonResponse
    {
        $payment = $this->entityManager->getRepository(Payment::class)->find($id);

        if (!$payment) {
            return $this->json(['data' => ['error' => 'Not found payment by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['data' => $payment], Response::HTTP_OK);
    }

    /**
     * CREATE - створити нову оплату
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/payments', name: 'post_payments', methods: [Request::METHOD_POST])]
    public function createPayment(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $membership = $this->entityManager->getRepository(Membership::class)->find($requestData['membershipId']);

        if (!$membership) {
            return $this->json(['data' => ['error' => 'Not found membership by id ' . $requestData['membershipId']]], Response::HTTP_NOT_FOUND);
        }

        $payment = new Payment();
        $payment->setMembership($membership);
        $payment->setAmount($requestData['amount']);
        $payment->setMethod($requestData['method']);
        $payment->setPaidAt(new \DateTimeImmutable($requestData['paidAt']));

        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        return $this->json(['data' => $payment], Response::HTTP_CREATED);
    }

    /**
     * UPDATE - оновити оплату за id
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/payments/{id}', name: 'put_payments', methods: [Request::METHOD_PUT])]
    public function updatePayment(Request $request, string $id): JsonResponse
    {
        $payment = $this->entityManager->getRepository(Payment::class)->find($id);

        if (!$payment) {
            return $this->json(['data' => ['error' => 'Not found payment by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);

        $membership = $this->entityManager->getRepository(Membership::class)->find($requestData['membershipId']);

        if (!$membership) {
            return $this->json(['data' => ['error' => 'Not found membership by id ' . $requestData['membershipId']]], Response::HTTP_NOT_FOUND);
        }

        $payment->setMembership($membership);
        $payment->setAmount($requestData['amount']);
        $payment->setMethod($requestData['method']);
        $payment->setPaidAt(new \DateTimeImmutable($requestData['paidAt']));

        $this->entityManager->flush();

        return $this->json(['data' => $payment], Response::HTTP_OK);
    }

    /**
     * DELETE - видалити оплату за id
     *
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/payments/{id}', name: 'delete_payments', methods: [Request::METHOD_DELETE])]
    public function deletePayment(string $id): JsonResponse
    {
        $payment = $this->entityManager->getRepository(Payment::class)->find($id);

        if (!$payment) {
            return $this->json(['data' => ['error' => 'Not found payment by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($payment);
        $this->entityManager->flush();

        return $this->json(['data' => 'Deleted payment by id ' . $id], Response::HTTP_OK);
    }
}
