@php
$tasks = getPrevNextTasks($empDetails->ein)
@endphp


@switch($tasks['current']['tasks_duty'])
@case ('verify_new_application')
@include('duties.verify_and_approve.verify_new_application')
@break
@endswitch