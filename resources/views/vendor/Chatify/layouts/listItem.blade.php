{{-- -------------------- Grouped Contact List -------------------- --}}
@if(isset($get) && $get == 'users' && isset($users) && count($users))
    @php
        $grouped = collect($users)->groupBy('group');
    @endphp
    @foreach($grouped as $group => $groupUsers)
        <div class="messenger-title"><span>{{ $group }}</span></div>
        @foreach($groupUsers as $user)
            <table class="messenger-list-item" data-contact="{{ $user['id'] }}">
                <tr data-action="0">
                    {{-- Avatar side --}}
                    <td style="position: relative">
                        <div class="avatar av-m"
                        style="background-image: url('{{ $user['avatar'] ? asset('storage/' . $user['avatar']) : asset('images/default-avatar.png') }}');">
                        </div>
                    </td>
                    {{-- center side --}}
                    <td>
                        <p data-id="{{ $user['id'] }}" data-type="user">
                            {{ strlen($user['name']) > 12 ? trim(substr($user['name'],0,12)).'..' : $user['name'] }}
                        </p>
                        <span>{{ ucfirst($user['role']) }}</span>
                    </td>
                </tr>
            </table>
        @endforeach
    @endforeach
@endif

{{-- Fallback to original Chatify logic for other cases --}}
@if($get == 'saved')
    <table class="messenger-list-item" data-contact="{{ Auth::user()->id }}">
        <tr data-action="0">
            {{-- Avatar side --}}
            <td>
            <div class="saved-messages avatar av-m">
                <span class="far fa-bookmark"></span>
            </div>
            </td>
            {{-- center side --}}
            <td>
                <p data-id="{{ Auth::user()->id }}" data-type="user">Saved Messages <span>You</span></p>
                <span>Save messages secretly</span>
            </td>
        </tr>
    </table>
@endif

@if($get == 'search_item')
<table class="messenger-list-item" data-contact="{{ $user->id }}">
    <tr data-action="0">
        {{-- Avatar side --}}
        <td>
        <div class="avatar av-m"
        style="background-image: url('{{ $user->avatar }}');">
        </div>
        </td>
        {{-- center side --}}
        <td>
            <p data-id="{{ $user->id }}" data-type="user">
            {{ strlen($user->name) > 12 ? trim(substr($user->name,0,12)).'..' : $user->name }}
        </td>

    </tr>
</table>
@endif

@if($get == 'sharedPhoto')
<div class="shared-photo chat-image" style="background-image: url('{{ $image }}')"></div>
@endif


