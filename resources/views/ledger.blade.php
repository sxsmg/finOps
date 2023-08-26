<!DOCTYPE html>
<html>
<head>
    <title>Ledger</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
</head>
<body>
    <h2>Ledger</h2>

    <h3>Latest Transactions</h3>
    <ul>
    
        @foreach ($transactions as $transaction)
            <li>
                {{ $transaction->description }} - {{ $transaction->amount }}
                
                @if ($user && $user->hasRole('admin'))
                    <form id="deleteTransactionForm-{{ $transaction->id }}" data-transaction-id="{{ $transaction->id }}">
                        @csrf
                        @method('DELETE')
                        <button class="delete-button" type="button">Delete</button>
                    </form>
                @endif  

                <form id="updateTransactionForm" data-transaction-id="{{ $transaction->id }}">
                    <input type="text" name="description" placeholder="New Description">
                    <input type="number" name="amount" placeholder="New Amount">
                    <button id="updateButton" type="button">Update</button>
                </form>

                <script>
                    $(document).ready(function() {
                        $('#updateButton').click(function() {
                            var transactionId = $('#updateTransactionForm').data('transaction-id');
                            var newDescription = $('input[name="description"]').val();
                            var newAmount = $('input[name="amount"]').val();
                            
                            var requestData = {
                                description: newDescription,
                                amount: newAmount
                            };
                            
                            $.ajax({
                                url: '/api/transactions/' + transactionId,
                                type: 'PUT',
                                headers: {
                                    'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: requestData,
                                success: function(response) {
                                    //console.log(response);
                                    location.reload(); // Reload the page after successful update
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error:', error);
                                }
                            });
                        });
                    });
                </script>

            </li>
        @endforeach
    </ul>

    <h3>Add New Transaction</h3>
    <form id="add-transaction-form">
        @csrf
        <input type="text" name="description" placeholder="Description">
        <input type="number" name="amount" placeholder="Amount">
        <button type="submit">Add Transaction</button>
    </form>

    <script>
        $(document).ready(function() {
            $('#add-transaction-form').submit(function(event) {
                event.preventDefault();

                // Get form data
                var formData = $(this).serialize();

                // Call the API to add a new transaction
                $.ajax({
                    url: '/api/transactions',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Handle success
                        location.reload();
                        // Refresh the transactions list or perform any other updates
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error('Error adding transaction:', error);
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.delete-button').click(function() {
                var transactionId = $(this).parent().data('transaction-id');

                $.ajax({
                    url: '/api/transactions/' + transactionId,
                    type: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Handle success
                        location.reload(); // Reload the page after successful deletion
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error('Error deleting transaction:', error);
                    }
                });
            });
        });
    </script>
</body>
</html>
