<script setup>
import UserAuthenticatedLayout from '@/Layouts/UserAuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted, watchEffect } from 'vue';

const props = defineProps({
    start_at: String,
    stay_time: Number,
    price: Number,

    stripe_public_key: String
});

const form = useForm({
    start_at: props.start_at,
    stay_time: props.stay_time,

    cardNumber: '',
    cardMonth: '',
    cardYear: '',
    cardCVC: '',
});

const send = () => {
    form.post(route('user.reserve.store'))
}

    let cardNumber = null
    let stripe, card;

    var elementStyles = {
      base: {
        color: '#32325D',
        fontWeight: 300,
        fontFamily: 'Source Code Pro, Consolas, Menlo, monospace',
        fontSize: '18px',
        fontColor: '#000',
        fontSmoothing: 'antialiased',

        '::placeholder': {
          color: '#CFD7DF',
        },
        ':-webkit-autofill': {
          color: '#e39f48',
        },
      },
      invalid: {
        color: '#fa755a',
        iconColor: '#fa755a',
      },
    };

  var elementClasses = {
    focus: 'focused',
    empty: 'empty',
    invalid: 'invalid',
  };

    onMounted(() => {
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeStripe);
      } else {
        initializeStripe();
      }
    });

    function initializeStripe() {
      stripe = window.Stripe(props.stripe_public_key);
      const elements = stripe.elements();

      cardNumber = elements.create('cardNumber', {
        style: elementStyles,
        classes: elementClasses,
      });
      cardNumber.mount('#card-number')

      // カードの有効期限
      const cardExpiry = elements.create('cardExpiry', {
        style: elementStyles,
        classes: elementClasses,
      });
      cardExpiry.mount('#card-expiry');

      // カードのCVC入力
      const cardCvc = elements.create('cardCvc', {
        style: elementStyles,
        classes: elementClasses,
      });
      cardCvc.mount('#card-cvc');
    }

    const handleSubmit = async () => {
      if (stripe && cardNumber) {
          stripe.createToken(cardNumber).then(function(result) {
          if (result.error) {
            // エラーを処理
            console.error(result.error.message);
          } else {
            // トークンを処理
            console.log(result.token);
          }
        });
      }
    };
</script>
<template>
    <Head title="予約確認" />

    <UserAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">予約確認</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <table class="min-w-full p-4 mt-4">
                      <tbody>
                        <tr>
                          <td class="w-1/2 text-right px-4 py-2">開始日時</td>
                          <td class="w-1/2 px-4 py-2">
                            {{ start_at }}
                          </td>
                        </tr>
                        <tr>
                          <td class="w-1/2 text-right px-4 py-2">使用時間</td>
                          <td class="w-1/2 px-4 py-2">
                            {{ stay_time }} 時間
                          </td>
                        </tr>
                        <tr>
                          <td class="w-1/2 text-right px-4 py-2">料金</td>
                          <td class="w-1/2 px-4 py-2">
                            {{ price }} 円
                          </td>
                        </tr>
                      </tbody>
                    </table>

                    <form @submit.prevent="handleSubmit">
                      <div class="flex justify-center mt-4">
                        <div class="w-1/2">
                          <div class="flex justify-start w-1/4">
                            <img src="/Stripelogo.png">
                          </div>
                          <label for="card_number">カード番号</label>
                          <div id="card-number" class="form-control p-2 border-solid border-2 rounded-lg"></div>
                        </div>
                      </div>

                      <div class="flex justify-center mt-4">
                        <div class="w-1/4 mx-1">
                          <label for="card_expiry">有効期限</label>
                          <div id="card-expiry" class="form-control p-2 border-solid border-2 rounded-lg"></div>
                        </div>
                        <div class="w-1/4 mx-1">
                          <label for="card-cvc">セキュリティコード</label>
                          <div id="card-cvc" class="form-control p-2 border-solid border-2 rounded-lg"></div>
                        </div>
                      </div>

                      <div class="flex justify-center mt-4 mb-16">
                          <button class="mx-4 py-2 w-1/2 bg-red-400 rounded-lg"
                              type="submit"    :disabled="form.processing">
                          支払う
                          </button>
                      </div>
                    </form>

                </div>
            </div>
        </div>
    </UserAuthenticatedLayout>
</template>
