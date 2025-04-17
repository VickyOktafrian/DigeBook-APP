<!DOCTYPE html>
<html>
<head>
    <title>Chatbot LibrAI</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h1>Tanya ke LibrAI</h1>

    <form method="POST" action="/ask">
        @csrf
        <div class="mb-3">
            <label for="question" class="form-label">Pertanyaan Anda:</label>
            <input type="text" class="form-control" name="question" id="question" required value="{{ old('question') }}">
        </div>
        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>

    @if(isset($answer))
        <hr>
        <h4>Pertanyaan:</h4>
        <p>{{ $question }}</p>

        <h4>Jawaban:</h4>
        <div class="alert alert-success">
            {!! nl2br(e($answer)) !!}
        </div>
    @endif
</body>
</html>
