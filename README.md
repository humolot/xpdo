<h1 id="pdox-documentation">xPdo Documentação</h1>
<h2 id="install">Instalar</h2>

<p>Incluir a classe xPdo.php em seu projeto.</p>
<pre><code>require_once("xPdo.php");</code></pre>

<h2 id="quick-usage">Uso Rápido</h2>
<pre class="sourceCode php"><code class="sourceCode php">require &#39;xPdo.php&#39;;

$config = [
    &#39;host&#39;      =&gt; &#39;localhost&#39;,
    &#39;driver&#39;    =&gt; &#39;mysql&#39;,
    &#39;database&#39;  =&gt; &#39;test&#39;,
    &#39;username&#39;  =&gt; &#39;root&#39;,
    &#39;password&#39;  =&gt; &#39;&#39;,
    &#39;charset&#39;   =&gt; &#39;utf8&#39;,
    &#39;collation&#39; =&gt; &#39;utf8_general_ci&#39;,
    &#39;prefix&#39;    =&gt; &#39;&#39;
];

$db = new xPdo($config);</code></pre>
<p>Parabéns! Agora você pode usar o xPdo.</p>
<p>Se você tiver um problema, você pode entrar em contato comigo</a>.</p>
<h1 id="detailed-usage-and-methods">Uso Detalhado e Métodos</h1>
<h2 id="contents">Conteúdo</h2>
<ul>
<li><a href="#encode">Encode/Decode</a><br /></li>
<li><a href="#hash">Radom Hash</a><br /></li>
<li><a href="#latlng">Lat and Lng</a><br /></li>
<li><a href="#limit_text">Limit Text</a><br /></li>
<li><a href="#dates">Date Format</a><br /></li>
<li><a href="#select">Select</a><br /></li>
<li><a href="#selectDetails">Select Functions (min, max, sum, avg, count)</a><br /></li>
<li><a href="#table">Table</a><br /></li>
<li><a href="#get-and-getall">get AND getAll</a><br /></li>
<li><a href="#join">join</a><br /></li>
<li><a href="#where---orwhere">where - orWhere</a><br /></li>
<li><a href="#grouped">grouped</a><br /></li>
<li><a href="#in---notin---orin---ornotin">in - notIn - orIn - orNotIn</a><br /></li>
<li><a href="#between---orbetween---notbetween---ornotbetween">between - orBetween - notBetween - orNotBetween</a><br /></li>
<li><a href="#like---orlike">like - orLike</a><br /></li>
<li><a href="#groupby">groupBy</a><br /></li>
<li><a href="#having">having</a><br /></li>
<li><a href="#orderby">orderBy</a><br /></li>
<li><a href="#limit">limit</a><br /></li>
<li><a href="#insert">insert</a><br /></li>
<li><a href="#update">update</a><br /></li>
<li><a href="#delete">delete</a><br /></li>
<li><a href="#analyze">analyze</a><br /></li>
<li><a href="#check">check</a><br /></li>
<li><a href="#checksum">checksum</a><br /></li>
<li><a href="#optimize">optimize</a><br /></li>
<li><a href="#repair">repair</a><br /></li>
<li><a href="#query">query</a><br /></li>
<li><a href="#insertid">insertId</a><br /></li>
<li><a href="#numrows">numRows</a><br /></li>
<li><a href="#error">error</a><br /></li>
<li><a href="#cache">cache</a><br /></li>
<li><a href="#querycount">queryCount</a><br /></li>
<li><a href="#getquery">getQuery</a><br /></li>
<li><a href="#escape">escape</a> - (Not yet)</li>
</ul>
<h2 id="methods">Methods</h2>

<h3 id="encode">encode/decode THOR</h3>
<pre class="sourceCode php"><code class="sourceCode php"># Enable openssl 
# Usage : string parameter, secret key
$db-&gt;thor(&#39;encode&#39;, &#39;String&#39;, &#39;Secret Key&#39;);
$db-&gt;thor(&#39;decode&#39;, &#39;String&#39;, &#39;Secret Key&#39;);

# Output: hash sha256 
</code></pre>

<h3 id="hash">generate radom hash</h3>
<pre class="sourceCode php"><code class="sourceCode php"># Usage : string parameter size to generate
$db-&gt;get_hash(&#39;48&#39;);

# Output: hash
</code></pre>

<h3 id="latlng">get lat and lng google maps</h3>
<pre class="sourceCode php"><code class="sourceCode php"># Usage : string parameter address
$db-&gt;getLatLong(&#39;São Paulo, SP, Brazil&#39;);

# Output: array latitude and longitude
</code></pre>

<h3 id="limit_text">limit text</h3>
<pre class="sourceCode php"><code class="sourceCode php"># Usage : string parameter and number limit characters
$db-&gt;text_limit(&#39;String&#39;,&#39;60&#39;);

# Output: text limited
</code></pre>

<h3 id="dates">date and datetime</h3>
<pre class="sourceCode php"><code class="sourceCode php"># Usage : date and format / en = yyyy-mm-dd / br = dd/mm/yyyy
$db-&gt;date(&#39;01/01/2018&#39;,&#39;en&#39;);
# Output: 2018-01-01
 
$db-&gt;datetime(&#39;01/01/2018 12:00:00&#39;,&#39;en&#39;);
# Output: 2018-01-01 12:00:00

$db-&gt;date(&#39;2018-01-01&#39;,&#39;br&#39;);
# Output: 01/01/2018

$db-&gt;datetime(&#39;01/01/2018 12:00:00&#39;,&#39;br&#39;);
# Output: 01/01/2018 12:00:00
</code></pre>


<h3 id="select">select</h3>
<pre class="sourceCode php"><code class="sourceCode php"># Usage 1: string parameter
$db-&gt;select(&#39;title, content&#39;);
$db-&gt;select(&#39;title AS t, content AS c&#39;);

# Usage2: array parameter
$db-&gt;select([&#39;title&#39;, &#39;content&#39;]);
$db-&gt;select([&#39;title AS t&#39;, &#39;content AS c&#39;]);</code></pre>
<h3 id="select-functions-min-max-sum-avg-count">select functions (min, max, sum, avg, count)</h3>
<pre class="sourceCode php"><code class="sourceCode php"># Usage 1:
$db-&gt;table(&#39;test&#39;)-&gt;max(&#39;price&#39;);

# Output: &quot;SELECT MAX(price) FROM test&quot;

# Usage 2:
$db-&gt;table(&#39;test&#39;)-&gt;count(&#39;id&#39;, &#39;total_row&#39;);

# Output: &quot;SELECT COUNT(id) AS total_row FROM test&quot;</code></pre>
<h3 id="table">table</h3>
<pre class="sourceCode php"><code class="sourceCode php"># Usage 1: string parameter
$db-&gt;table(&#39;table&#39;);
$db-&gt;table(&#39;table1, table2&#39;);
$db-&gt;table(&#39;table1 AS t1, table2 AS t2&#39;);

# Usage2: array parameter
$db-&gt;table([&#39;table1&#39;, &#39;table2&#39;]);
$db-&gt;table([&#39;table1 AS t1&#39;, &#39;table2 AS t2&#39;]);</code></pre>
<h3 id="get-and-getall">get AND getAll</h3>
<pre class="sourceCode php"><code class="sourceCode php"># get(): return 1 record.
# getAll(): return multiple records.

$db-&gt;table(&#39;test&#39;)-&gt;getAll();   // &quot; SELECT * FROM test &quot;
$db-&gt;select(&#39;username&#39;)-&gt;table(&#39;users&#39;)-&gt;where(&#39;status&#39;, 1)-&gt;getAll();  // &quot; SELECT username FROM users WHERE status = &#39;1&#39; &quot;

$db-&gt;select(&#39;title&#39;)-&gt;table(&#39;pages&#39;)-&gt;where(&#39;id&#39;, 17)-&gt;get(); // &quot; SELECT title FROM pages WHERE id = &#39;17&#39; LIMIT 1 &quot;

# Results 
$records = $db->table('test')->getAll();
foreach ((array) $records as $record) {
	echo  $record->title;
}</code></pre>
<h3 id="join">join</h3>
<pre class="sourceCode php"><code class="sourceCode php"># Usage 1:
$db-&gt;table(&#39;foo&#39;)-&gt;join(&#39;bar&#39;, &#39;foo.field&#39;, &#39;bar.field&#39;)-&gt;getAll();
$db-&gt;table(&#39;foo&#39;)-&gt;leftJoin(&#39;bar&#39;, &#39;foo.field&#39;, &#39;bar.field&#39;)-&gt;getAll();
$db-&gt;table(&#39;foo&#39;)-&gt;rightJoin(&#39;bar&#39;, &#39;foo.field&#39;, &#39;bar.field&#39;)-&gt;get();
$db-&gt;table(&#39;foo&#39;)-&gt;innerJoin(&#39;bar&#39;, &#39;foo.field&#39;, &#39;bar.field&#39;)-&gt;get();

# Usage 2:
$db-&gt;table(&#39;foo&#39;)-&gt;join(&#39;bar&#39;, &#39;foo.field&#39;, &#39;=&#39;, &#39;bar.field&#39;)-&gt;getAll();
$db-&gt;table(&#39;foo&#39;)-&gt;leftJoin(&#39;bar&#39;, &#39;foo.field&#39;, &#39;=&#39;, &#39;bar.field&#39;)-&gt;getAll();
$db-&gt;table(&#39;foo&#39;)-&gt;rightJoin(&#39;bar&#39;, &#39;foo.field&#39;, &#39;=&#39;, &#39;bar.field&#39;)-&gt;get();
$db-&gt;table(&#39;foo&#39;)-&gt;innerJoin(&#39;bar&#39;, &#39;foo.field&#39;, &#39;=&#39;, &#39;bar.field&#39;)-&gt;get();</code></pre>
<h3 id="where---orwhere">where - orWhere</h3>
<pre class="sourceCode php"><code class="sourceCode php"># Usage 1: array parameter
$where = [
    &#39;name&#39; =&gt; &#39;Burak&#39;,
    &#39;age&#39; =&gt; 23,
    &#39;status&#39; =&gt; 1
];
$db-&gt;where($where);

# Usage 2:
$db-&gt;where(&#39;status&#39;, 2);
$db-&gt;where(&#39;status&#39;, 1)-&gt;where(&#39;name&#39;, &#39;burak&#39;);
$db-&gt;where(&#39;status&#39;, 1)-&gt;orWhere(&#39;status&#39;, 2);

# Usage 3:
$db-&gt;where(&#39;age&#39;, &#39;&gt;&#39;, 20);
$db-&gt;where(&#39;age&#39;, &#39;&gt;&#39;, 20)-&gt;orWhere(&#39;age&#39;, &#39;&lt;&#39;, 30);

# Usage 4:
$db-&gt;where(&#39;status = ? AND age = ?&#39;, [1, 20]);
$db-&gt;where(&#39;status = ? AND title = ?&#39;, [0, &#39;example title&#39;]);</code></pre>
<h3 id="grouped">grouped</h3>
<pre class="sourceCode php"><code class="sourceCode php">$db-&gt;table(&#39;users&#39;)
    -&gt;grouped(function($q) {
        $q-&gt;where(&#39;country&#39;, &#39;TURKEY&#39;)-&gt;orWhere(&#39;country&#39;, &#39;ENGLAND&#39;);
    })
    -&gt;where(&#39;status&#39;, 1)
    -&gt;getAll();


$key = 10;
$db-&gt;table(&#39;users&#39;)
    -&gt;grouped(function($q) use ($key) {
        $q-&gt;where(&#39;key_field&#39;, $key)-&gt;orWhere(&#39;status&#39;, 0);
    })
    -&gt;where(&#39;status&#39;, 1)
    -&gt;getAll();</code></pre>
<h3 id="in---notin---orin---ornotin">in - notIn - orIn - orNotIn</h3>
<pre class="sourceCode php"><code class="sourceCode php">$db-&gt;in(&#39;page&#39;, [&#39;about&#39;, &#39;contact&#39;, &#39;products&#39;]);
$db-&gt;orIn(&#39;id&#39;, [1, 2, 3]);
$db-&gt;notIn(&#39;age&#39;, [20, 21, 22, 23]);
$db-&gt;orNotIn(&#39;age&#39;, [30, 31, 32, 32]);</code></pre>
<h3 id="between---orbetween---notbetween---ornotbetween">between - orBetween - notBetween - orNotBetween</h3>
<pre class="sourceCode php"><code class="sourceCode php">$db-&gt;between(&#39;age&#39;, 10, 20);
$db-&gt;orBetween(&#39;age&#39;, 20, 30);
$db-&gt;notBetween(&#39;year&#39;, 2010, 2015);
$db-&gt;orNotBetween(&#39;year&#39;, 2005, 2009);</code></pre>
<h3 id="like---orlike">like - orLike</h3>
<pre class="sourceCode php"><code class="sourceCode php">$db-&gt;like(&#39;title&#39;, &#39;%burak%&#39;);      // &quot; title LIKE &#39;%burak%&#39; &quot;
$db-&gt;like(&#39;title&#39;, &#39;humolot%&#39;);   // &quot; title LIKE &#39;humolot%&#39; &quot;
$db-&gt;like(&#39;title&#39;, &#39;%humolot&#39;);   // &quot; title LIKE &#39;%humolot&#39; &quot;

$db-&gt;like(&#39;tag&#39;, &#39;%php%&#39;)-&gt;orLike(&#39;tag&#39;, &#39;%web%&#39;);
$db-&gt;like(&#39;tag&#39;, &#39;%php%&#39;)-&gt;orLike(&#39;tag&#39;, &#39;web%&#39;);
$db-&gt;like(&#39;tag&#39;, &#39;%php%&#39;)-&gt;orLike(&#39;tag&#39;, &#39;%web&#39;);</code></pre>
<h3 id="groupby">groupBy</h3>
<pre class="sourceCode php"><code class="sourceCode php"># Usage 1: string parameter
$db-&gt;groupBy(&#39;country&#39;);
$db-&gt;groupBy(&#39;country, city&#39;);

# Usage 2: array parameter
$db-&gt;groupBy([&#39;country&#39;, &#39;city&#39;]);</code></pre>
<h3 id="having">having</h3>
<pre class="sourceCode php"><code class="sourceCode php">$db-&gt;having(&#39;AVG(price)&#39;, 2000);    // &quot; AVG(price) &gt; 2000 &quot;
$db-&gt;having(&#39;AVG(price)&#39;, &#39;&gt;=&#39;, 3000);  // &quot; AVG(price) &gt;= 3000 &quot;
$db-&gt;having(&#39;SUM(age) &lt;= ?&#39;, [50]); // &quot; SUM(age) &lt;= 50 &quot;</code></pre>
<h3 id="orderby">orderBy</h3>
<pre class="sourceCode php"><code class="sourceCode php">$db-&gt;orderBy(&#39;id&#39;); // &quot; ORDER BY id ASC
$db-&gt;orderBy(&#39;id DESC&#39;);
$db-&gt;orderBy(&#39;id&#39;, &#39;desc&#39;);
$db-&gt;orderBy(&#39;rand()&#39;); // &quot; ORDER BY rand() &quot;</code></pre>
<h3 id="limit">limit</h3>
<pre class="sourceCode php"><code class="sourceCode php">$db-&gt;limit(10);     // &quot; LIMIT 10 &quot;
$db-&gt;limit(10, 20); // &quot; LIMIT 10, 20 &quot;</code></pre>
<h3 id="insert">insert</h3>
<pre class="sourceCode php"><code class="sourceCode php">$data = array(
    'title' => 'test',
    'content' => 'Lorem ipsum dolor sit amet...',
    'time' => time(),
    'status' => 1
);

OR

$data = array();
$data['title'] = 'test';
$data['content'] = 'Lorem ipsum dolor sit amet...';
$data['time'] = time();
$data['status'] = 1;

OR

$data = [
    &#39;title&#39; =&gt; &#39;test&#39;,
    &#39;content&#39; =&gt; &#39;Lorem ipsum dolor sit amet...&#39;,
    &#39;time&#39; =&gt; time(),
    &#39;status&#39; =&gt; 1
];

$db-&gt;table(&#39;pages&#39;)-&gt;insert($data);</code></pre>
<h3 id="update">update</h3>
<pre class="sourceCode php"><code class="sourceCode php">$data = array(
    'username' => 'humolot',
    'password' => md5(&#39;demo-password&#39;),
    'activation' => 1,
    'status' => 1
);

OR

$data = array();
$data['username'] = 'humolot';
$data['password'] = md5(&#39;demo-password&#39;);
$data['activation'] = 1;
$data['status'] = 1;

OR

$data = [
    &#39;username&#39; =&gt; &#39;humolot&#39;,
    &#39;password&#39; =&gt; md5(&#39;demo-password&#39;),
    &#39;activation&#39; =&gt; 1,
    &#39;status&#39; =&gt; 1
];

$db-&gt;table(&#39;users&#39;)-&gt;where(&#39;id&#39;, 10)-&gt;update($data);</code></pre>
<h3 id="delete">delete</h3>
<pre class="sourceCode php"><code class="sourceCode php">$db-&gt;table(&#39;users&#39;)-&gt;where(&#39;id&#39;, 5)-&gt;delete();</code></pre>
<h3 id="analyze">analyze</h3>
<pre class="sourceCode php"><code class="sourceCode php">$query = $db-&gt;table(&#39;users&#39;)-&gt;analyze();
var_dump($query);

# Output:
# &quot;ANALYZE TABLE users&quot;</code></pre>
<h3 id="check">check</h3>
<pre class="sourceCode php"><code class="sourceCode php">$query = $db-&gt;table([&#39;users&#39;, &#39;pages&#39;])-&gt;check();
var_dump($query);

# Output:
# &quot;CHECK TABLE users, pages&quot;</code></pre>
<h3 id="checksum">checksum</h3>
<pre class="sourceCode php"><code class="sourceCode php">$query = $db-&gt;table([&#39;users&#39;, &#39;pages&#39;])-&gt;checksum();
var_dump($query);

# Output:
# &quot;CHECKSUM TABLE users, pages&quot;</code></pre>
<h3 id="optimize">optimize</h3>
<pre class="sourceCode php"><code class="sourceCode php">$query = $db-&gt;table([&#39;users&#39;, &#39;pages&#39;])-&gt;optimize();
var_dump($query);

# Output:
# &quot;OPTIMIZE TABLE users, pages&quot;</code></pre>
<h3 id="repair">repair</h3>
<pre class="sourceCode php"><code class="sourceCode php">$query = $db-&gt;table([&#39;users&#39;, &#39;pages&#39;])-&gt;repair();
var_dump($query);

# Output:
# &quot;REPAIR TABLE users, pages&quot;</code></pre>
<h3 id="query">query</h3>
<pre class="sourceCode php"><code class="sourceCode php">$ds = $db->query("SELECT * FROM test WHERE id = '10' AND status = '1'");

OR

$db-&gt;query(&#39;SELECT * FROM test WHERE id = ? AND status = ?&#39;, [10, 1]);</code></pre>
<h3 id="insertid">insertId</h3>
<pre class="sourceCode php"><code class="sourceCode php">$data = array(
    'title' => 'test',
    'content' => 'Lorem ipsum dolor sit amet...',
    'time' => time(),
    'status' => 1
);

OR 

$data = array();
$data['title'] = 'test';
$data['content'] = 'Lorem ipsum dolor sit amet...';
$data['time'] = time();
$data['status'] = 1;

OR

$data = [
    &#39;title&#39; =&gt; &#39;test&#39;,
    &#39;content&#39; =&gt; &#39;Lorem ipsum dolor sit amet...&#39;,
    &#39;time&#39; =&gt; time(),
    &#39;status&#39; =&gt; 1
];

$db-&gt;table(&#39;pages&#39;)-&gt;insert($data);

var_dump($db-&gt;insertId());</code></pre>
<h3 id="numrows">numRows</h3>
<pre class="sourceCode php"><code class="sourceCode php">$db-&gt;select(&#39;id, title&#39;)-&gt;table(&#39;test&#39;)-&gt;where(&#39;status&#39;, 1)-&gt;orWhere(&#39;status&#39;, 2)-&gt;getAll();

var_dump($db-&gt;numRows());</code></pre>
<h3 id="error">error</h3>
<pre class="sourceCode php"><code class="sourceCode php">$db-&gt;error();</code></pre>
<h3 id="cache">cache</h3>
<pre class="sourceCode php"><code class="sourceCode php"># Usage: ...-&gt;cache($time)-&gt;...
$db-&gt;table(&#39;pages&#39;)-&gt;where(&#39;slug&#39;, &#39;example-page.html&#39;)-&gt;cache(60)-&gt;get(); // cache time: 60 seconds</code></pre>
<h3 id="querycount">queryCount</h3>
<pre class="sourceCode php"><code class="sourceCode php">$db-&gt;queryCount(); // The number of all SQL queries on the page until the end of the beginning.</code></pre>
<h3 id="getquery">getQuery</h3>
<pre class="sourceCode php"><code class="sourceCode php">$db-&gt;getQuery(); // Last SQL Query.</code></pre>




</div></div></div>
