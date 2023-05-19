<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Condominiul | INVOICE</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }
    .gray {
        background-color: lightgray
    }
</style>

</head>
<body>

  <table width="100%">
    <tr>
        <td valign="top">
            {{-- <img src="{{asset('images/meteor-logo.png')}}" alt="" width="150"/> --}}
        </td>
        <td align="right">

            <pre>
                Fecha: {{ $payment->date }}
                {{-- Company address
                Tax ID
                phone
                fax --}}
            </pre>
        </td>
    </tr>

  </table>

  <table width="100%">
    <tr>
        <td><strong>Dirección Condominio:</strong></td>
        <td> Av de las palmas santa maria atarasquillo, Toluca, 52044</td>
    </tr>

  </table>

  <br/>

  <table width="100%">
    <thead style="background-color: lightgray;">
      <tr>
        <th>#</th>
        <th>Description</th>
        <th>Quantity</th>
        <th>Unit Price $</th>
        <th>Total $</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">{{ $payment->id }}</th>
        <td>{{ $payment->description }}</td>
        <td align="right">1</td>
        <td align="right">{{ number_format($payment->amount) }}</td>
        <td align="right">{{ number_format($payment->amount) }}</td>
      </tr>

    </tbody>

    <tfoot>
        <tr>
            <td colspan="3"></td>
            <td align="right">Subtotal $</td>
            <td align="right">{{ number_format($payment->amount) }}</td>
        </tr>
        {{-- <tr>
            <td colspan="3"></td>
            <td align="right">Tax $</td>
            <td align="right">294.3</td>
        </tr> --}}
        <tr>
            <td colspan="3"></td>
            <td align="right">Total $</td>
            <td align="right" class="gray">$ {{ number_format($payment->amount) }}</td>
        </tr>
    </tfoot>
  </table>

</body>
</html>
