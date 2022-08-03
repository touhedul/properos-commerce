@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.email') 
@section('content')
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="margin-bottom: 0px;">
    <tr>
        <td style="padding: 40px; padding-bottom: 0px; font-family: sans-serif; font-size: 20px; line-height: 27px; color: #666666; font-size: 16px">
            {{$email_data->title}}
        </td>
    </tr>
   {{--  <tr>
        <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 20px; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">
            There is a new revision for project <b>{{$project_name}}</b>, follow the link above to open the current revision.
            <br>
        </td>
    </tr> --}}
    <tr>
        <tr>
            <td style="padding: 40px; padding-top: 10px; font-family: sans-serif; font-size: 20px; line-height: 27px; color: #666666; font-size: 14px; text-align: justify">
                <b>Customer Name:</b> {{$email_data->name}}<br>
                <b>Customer Email:</b> {{$email_data->from}}<br>
                <b>Customer Phone:</b> {{$email_data->phone ? $email_data->phone : 'Not available'}}<br><br>
                <b>Message:</b> {{$email_data->message}}
            </td>
        </tr>
</table>
@endsection