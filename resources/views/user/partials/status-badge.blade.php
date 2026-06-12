@if($status === 'pending')
<span
    class="inline-block text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-yellow-100 text-yellow-800">Pending</span>
@elseif($status === 'negotiating')
<span
    class="inline-block text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-blue-100 text-blue-800">Negotiating</span>
@elseif($status === 'awaiting_payment')
<span
    class="inline-block text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-purple-100 text-purple-800">Awaiting
    Payment</span>
@elseif($status === 'paid')
<span
    class="inline-block text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-orange-100 text-orange-800">Paid
    (Escrowed)</span>
@elseif($status === 'completed')
<span
    class="inline-block text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-green-100 text-green-800">Completed</span>
@elseif($status === 'rejected')
<span
    class="inline-block text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-red-100 text-red-800">Rejected
    / Canceled</span>
@endif