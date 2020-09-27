<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{!! config('app.name') !!} | Annotate接口文档</title>
</head>
<body>

@foreach($annotates as $annotate)
    <h4>用户登录</h4>

    <h5>简要描述</h5>
    <ul>
        <li>用户登录接口</li>
    </ul>

    <h5>请求URL</h5>
    <ul>
        <li><code></code></li>
    </ul>

    <h5>请求方式</h5>
    <ul>
        <li>POST</li>
    </ul>

    <h5>参数</h5>
    <table>
        <thead>
        <tr>
            <th style="text-align: left;">参数名</th>
            <th style="text-align: left;">必选</th>
            <th style="text-align: left;">类型</th>
            <th>说明</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="text-align: left;">username</td>
            <td style="text-align: left;">是</td>
            <td style="text-align: left;">string</td>
            <td>用户名</td>
        </tr>
        <tr>
            <td style="text-align: left;">password</td>
            <td style="text-align: left;">是</td>
            <td style="text-align: left;">string</td>
            <td>密码</td>
        </tr>
        </tbody>
    </table>

    <h5>返回示例</h5>
    <pre>
    <code class="language-json">
        {
            "error_code": 0,
            "data": {
                "uid": "1",
                "username": "12154545",
                "name": "吴系挂",
                "groupid": 2 ,
                "reg_time": "1436864169",
                "last_login_time": "0"
            }
        }
    </code>
</pre>

    <h5>返回参数说明</h5>
    <table>
        <thead>
        <tr>
            <th style="text-align: left;">参数名</th>
            <th style="text-align: left;">类型</th>
            <th>说明</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="text-align: left;">groupid</td>
            <td style="text-align: left;">int</td>
            <td>用户组id，1：超级管理员；2：普通用户</td>
        </tr>
        </tbody>
    </table>

    <h5>备注</h5>
    <ul>
        <li>更多返回错误代码请看首页的错误代码描述</li>
    </ul>
@endforeach
</body>
</html>