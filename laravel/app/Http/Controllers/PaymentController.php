<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    /**
     * READ - список усіх оплат (разом з абонементом)
     *
     * @return mixed
     */
    public function getPayments(): mixed
    {
        $payments = Payment::with('membership')->get();

        return response()->json(['data' => $payments], Response::HTTP_OK);
    }

    /**
     * READ - одна оплата за id (разом з абонементом)
     *
     * @param string $id
     * @return mixed
     */
    public function getPaymentItem(string $id): mixed
    {
        $payment = Payment::with('membership')->find($id);

        if (!$payment) {
            return response()->json(['data' => ['error' => 'Not found payment by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $payment], Response::HTTP_OK);
    }

    /**
     * CREATE - створити нову оплату
     *
     * @param Request $request
     * @return mixed
     */
    public function createPayment(Request $request): mixed
    {
        $membership = Membership::find($request->input('membership_id'));

        if (!$membership) {
            return response()->json(['data' => ['error' => 'Not found membership by id ' . $request->input('membership_id')]], Response::HTTP_NOT_FOUND);
        }

        $payment = Payment::create($request->all());

        return response()->json(['data' => $payment->load('membership')], Response::HTTP_CREATED);
    }

    /**
     * UPDATE - оновити оплату за id
     *
     * @param Request $request
     * @param string $id
     * @return mixed
     */
    public function updatePayment(Request $request, string $id): mixed
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json(['data' => ['error' => 'Not found payment by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        if ($request->has('membership_id') && !Membership::find($request->input('membership_id'))) {
            return response()->json(['data' => ['error' => 'Not found membership by id ' . $request->input('membership_id')]], Response::HTTP_NOT_FOUND);
        }

        $payment->update($request->all());

        return response()->json(['data' => $payment->load('membership')], Response::HTTP_OK);
    }

    /**
     * DELETE - видалити оплату за id
     *
     * @param string $id
     * @return mixed
     */
    public function deletePayment(string $id): mixed
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json(['data' => ['error' => 'Not found payment by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $payment->delete();

        return response()->json(['data' => 'Deleted payment by id ' . $id], Response::HTTP_OK);
    }
}
