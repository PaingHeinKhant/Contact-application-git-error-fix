@extends('layouts.app')
@section('content')


    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <table class="table table-borderless">
                    <thead class="items rounded-3" >
                    <tr class="underline">
                        <th class=" m-0 h6 fw-bold d-flex align-items-center">
                            <div class="">
                                Name
                            </div>
                        </th>
                        <th class="my-5 py-3 h6 fw-bold">Email</th>
                        <th class="my-5 py-3 h6 fw-bold">Phone Number</th>
                        <th class="my-5 py-3 h6 fw-bold">Job Title and Company</th>
                        <th></th>
                    </tr>
                    <span class="text-black-50">CONTACTS ( {{ \App\Models\Contact::onlyTrashed()->count() }} )</span>
                    </thead>

                    <tbody>
                    @forelse($contacts as $contact)
                        <tr class="show">
                            <td class="d-flex align-items-center my-1" >
                                <div class="d-flex align-items-center">

                                    <div class="me-4 show-inverse">
                                        @if(isset($contact->image))
                                            <img src="{{ asset("storage/".$contact->image) }}" width="36px" height="36px" id="inputImageFile" class="rounded rounded-circle " alt="">
                                        @else
                                            <div class="rand" style="background:{{\App\Models\Contact::randBackgroundColor()}} ">{{ \Illuminate\Support\Str::substr(ucfirst($contact->firstName),0, 1) }}</div>
                                        @endif
                                    </div>
                                    <label>{{ ucfirst($contact->firstName) }}  {{ ucfirst($contact->lastName) }}</label>
                                </div>
                            </td>

                            <td  class=" my-1">
                                {{ $contact->email }}
                            </td>
                            <td  class=" my-1">
                                {{ $contact->phone }}
                            </td>
                            <td  class=" my-1">
                                {{ $contact->company }}
                            </td>

                            <td class="d-flex ">
                                <form class="d-block" method="POST"  action="{{route('contact.destroy',$contact->id)}}">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-outline-danger me-3">
                                        <i class="bi bi-trash fs-6 me-1"></i>
                                        <span class="my-1" >Delete</span>
                                    </button>
                                </form>
                                <form class="d-block" method="get"  action="{{route('restore',$contact->id)}}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary me-3">
                                        <i class="bi bi-recycle fs-6 me-1"></i>
                                        <span class="my-1" >Restore</span>
                                    </button>
                                </form>
                            </td>
                    @empty

                        <tr>
                            <td colspan=4 class="text-center">There is no Content</td>
                        </tr>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="">
                    {{ $contacts->onEachSide(2)->links() }}
                </div>
            </div>
        </div>
    </div>

@stop
