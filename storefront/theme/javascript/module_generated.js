#register {
	margin: 20px;
}@CHARSET "UTF-8";

#roof #logo {
	position: absolute;
	top: 25px;
	left: 15px;
}

#roof #cart_box {
	position: absolute;
	top: 15px;
	right:350px;
}



#currency {
	width: 75px;
	position: absolute;
	top: 15px;
	left: 425px;
	color: #999;
	line-height: 17px;
}
#currency a {
	display: inline-block;
	padding: 2px 4px;
	border: 1px solid #CCC;
	color: #999;
	text-decoration: none;
	margin-right: 2px;
	margin-bottom: 2px;
}
#currency a b {
	color: #000;
	text-decoration: none;
}



#roof #search {
	position: absolute;
	top: 15px;
	right: 0px;
	width: 298px;
	z-index: 15;
}


#roof .button-search {
	position: absolute;
	left: 0px;
	background: url('../image/button-search.png') center center no-repeat;
	width: 28px;
	height: 24px;
	border-right: 1px solid #CCCCCC;
	cursor: pointer;
}
#roof #search input {
	background: #FFF;
	padding: 1px 1px 1px 33px;
	width: 262px;
	height: 21px;
	border: 1px solid #CCCCCC;
	-webkit-border-radius: 3px 3px 3px 3px;
	-moz-border-radius: 3px 3px 3px 3px;
	-khtml-border-radius: 3px 3px 3px 3px;
	border-radius: 3px 3px 3px 3px;
	-webkit-box-shadow: 0px 2px 0px #F0F0F0;
	-moz-box-shadow: 0px 2px 0px #F0F0F0;
	box-shadow: 0px 2px 0px #F0F0F0;
}
#roof #welcome {
	position: absolute;
	top: 47px;
	right: 0px;
	z-index: 5;
	width: 298px;
	text-align: right;
	color: #999999;
}
#roof .links {
	position: absolute;
	right: 0px;
	bottom: 45px;
	font-size: 10px;
	padding-right: 10px;
}
#roof .links a {
	float: left;
	display: block;
	padding: 0px 0px 0px 7px;
	color: #38B0E3;
	text-decoration: none;
	font-size: 12px;
}
#roof .links a + a {
	margin-left: 8px;
	border-left: 1px solid #CCC;
}





/* menu */
#menu {
	position: absolute;
	left: 0px;
	right:0px;
	bottom: 0px;
	
	background: #585858;
	border-bottom: 1px solid #000000;
	height: 37px;
	margin-bottom: 0px;
	-webkit-border-radius: 5px 5px 5px 5px;
	-moz-border-radius: 5px 5px 5px 5px;
	-khtml-border-radius: 5px 5px 5px 5px;
	border-radius: 5px 5px 5px 5px;
	-webkit-box-shadow: 0px 2px 2px #DDDDDD;
	-moz-box-shadow: 0px 2px 2px #DDDDDD;
	box-shadow: 0px 2px 2px #DDDDDD;
	padding: 0px 5px;
}
#menu ul {
	list-style: none;
	margin: 0;
	padding: 0;
}
#menu > ul > li {
	position: relative;
	float: left;
	z-index: 20;
	padding: 6px 5px 5px 0px;
}
#menu > ul > li:hover {
}
#menu > ul > li > a {
	font-size: 13px;
	color: #FFF;
	line-height: 14px;
	text-decoration: none;
	display: block;
	padding: 6px 10px 6px 10px;
	margin-bottom: 5px;
	z-index: 6;
	position: relative;
}
#menu > ul > li:hover > a {
	background: #000000;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	-khtml-border-radius: 5px;
	border-radius: 5px;
}
#menu > ul > li > div {
	display: none;
	background: #FFFFFF;
	position: absolute;
	z-index: 5;
	padding: 5px;
	border: 1px solid #000000;
	-webkit-border-radius: 0px 0px 5px 5px;
	-moz-border-radius: 0px 0px 5px 5px;
	-khtml-border-radius: 0px 0px 5px 5px;
	border-radius: 0px 0px 5px 5px;
	background: url('../image/menu.png');
}
#menu > ul > li:hover > div {
	display: table;
}
#menu > ul > li > div > ul {
	display: table-cell;
}
#menu > ul > li ul + ul {
	padding-left: 20px;
}
#menu > ul > li ul > li > a {
	text-decoration: none;
	padding: 4px;
	color: #FFFFFF;
	display: block;
	white-space: nowrap;
	min-width: 120px;
}
#menu > ul > li ul > li > a:hover {
	background: #000000;
}
#menu > ul > li > div > ul > li > a {
	color: #FFFFFF;
}



#bottom_links h3 {
	color: #000000;
	font-size: 14px;
	margin-top: 0px;
	margin-bottom: 8px;
}
#bottom_links .column {
	float: left;
	width: 25%;
	min-height: 100px;
}
#bottom_links .column ul {
	margin-top: 0px;
	margin-left: 8px;
	padding-left: 12px;
}
#bottom_links .column ul li {
	margin-bottom: 3px;
}
#bottom_links .column a {
	text-decoration: none;
	color: #000;
}
#bottom_links .column a:hover {
	text-decoration: underline;
}


.breadcrumb {
	color: #CCCCCC;
	margin-left: 10px;
	margin-top: 10px;
	margin-right: 10px;
	margin-bottom: 10px;
}


#category_product_list {
	margin: 10px;
	
}







/* Product */
.product-info {
	overflow: auto;
	margin: 20px;
	
}
.product-info > .left {
	float: left;
	margin-right: 15px;
}
.product-info > .left + .right {
	margin-left: 265px;
}
.product-info .image {
	border: 1px solid #E7E7E7;
	float: left;
	margin-bottom: 20px;
	padding: 10px;
	text-align: center;
}
.product-info .image-additional {
	width: 260px;
	margin-left: -10px;
	clear: both;
	overflow: hidden;
}
.product-info .image-additional img {
	border: 1px solid #E7E7E7;
}
.product-info .image-additional a {
	float: left;
	display: block;
	margin-left: 10px;
	margin-bottom: 10px;
}
.product-info .description {
	border-top: 1px solid #E7E7E7;
	border-bottom: 1px solid #E7E7E7;
	padding: 5px 5px 10px 5px;
	margin-bottom: 10px;
	line-height: 20px;
	color: #4D4D4D;
}
.product-info .description span {
	color: #38B0E3;
}
.product-info .description a {
	color: #4D4D4D;
	text-decoration: none;
}
.product-info .price {
	overflow: auto;
	border-bottom: 1px solid #E7E7E7;
	padding: 0px 5px 10px 5px;
	margin-bottom: 10px;
	font-size: 15px;
	font-weight: bold;
	color: #333333;
}
.product-info .price-old {
	color: #F00;
	text-decoration: line-through;
}
.product-info .price-new {
}
.product-info .price-tax {
	font-size: 12px;
	font-weight: normal;
	color: #999;
}
.product-info .price .reward {
	font-size: 12px;
	font-weight: normal;
	color: #999;
}
.product-info .price .discount {
	font-weight: normal;
	font-size: 12px;
	color: #4D4D4D;
}
.product-info .options {
	border-bottom: 1px solid #E7E7E7;
	padding: 0px 5px 10px 5px;
	margin-bottom: 10px;
	color: #000000;
}
.product-info .option-image {
	margin-top: 3px;
	margin-bottom: 10px;
}
.product-info .option-image label {
	display: block;
	width: 100%;
	height: 100%;
}
.product-info .option-image img {
	margin-right: 5px;
	border: 1px solid #CCCCCC;
	cursor: pointer;
}
.product-info .cart {
	border-bottom: 1px solid #E7E7E7;
	padding: 0px 5px 10px 5px;
	margin-bottom: 20px;
	color: #4D4D4D;
	overflow: auto;
}
.product-info .cart div {
	float: left;
	vertical-align: middle;
}
.product-info .cart div > span {
	padding-top: 7px;
	display: block;
	color: #999;
}
.product-info .cart .minimum {
	padding-top: 5px;
	font-size: 11px;
	color: #999;
	clear: both;
}
.product-info .review {
	color: #4D4D4D;
	border-top: 1px solid #E7E7E7;
	border-left: 1px solid #E7E7E7;
	border-right: 1px solid #E7E7E7;
	margin-bottom: 10px;
}
.product-info .review > div {
	padding: 8px;
	border-bottom: 1px solid #E7E7E7;
	line-height: 20px;
}
.product-info .review > div > span {
	color: #38B0E3;
}
.product-info .review .share {
	overflow: auto;
	line-height: normal;
}
.product-info .review .share a {
	text-decoration: none;
}
.review-list {
	padding: 10px;
	overflow: auto;
	margin-bottom: 20px;
	border: 1px solid #EEEEEE;
}
.review-list .author {
	float: left;
	margin-bottom: 20px;
}
.review-list .rating {
	float: right;
	margin-bottom: 20px;
}
.review-list .text {
	clear: both;
}
.attribute {
	border-collapse: collapse;
	width: 100%;
	border-top: 1px solid #DDDDDD;
	border-left: 1px solid #DDDDDD;
	margin-bottom: 20px;
}
.attribute thead td, .attribute thead tr td:first-child {
	color: #000000;
	font-size: 14px;
	font-weight: bold;
	background: #F7F7F7;
	text-align: left;
}
.attribute tr td:first-child {
	color: #000000;
	font-weight: bold;
	text-align: right;
	width: 20%;
}
.attribute td {
	padding: 7px;
	color: #4D4D4D;
	text-align: center;
	vertical-align: top;
	border-right: 1px solid #DDDDDD;
	border-bottom: 1px solid #DDDDDD;
}
.compare-info {
	border-collapse: collapse;
	width: 100%;
	border-top: 1px solid #DDDDDD;
	border-left: 1px solid #DDDDDD;
	margin-bottom: 20px;
}
.compare-info thead td, .compare-info thead tr td:first-child {
	color: #000000;
	font-size: 14px;
	font-weight: bold;
	background: #F7F7F7;
	text-align: left;
}
.compare-info tr td:first-child {
	color: #000000;
	font-weight: bold;
	text-align: right;
}
.compare-info td {
	padding: 7px;
	width: 20%;
	color: #4D4D4D;
	text-align: center;
	vertical-align: top;
	border-right: 1px solid #DDDDDD;
	border-bottom: 1px solid #DDDDDD;
}
.compare-info .name a {
	font-weight: bold;
}
.compare-info .price-old {
	font-weight: bold;
	color: #F00;
	text-decoration: line-through;
}
.compare-info .price-new {
	font-weight: bold;
}
/* wishlist */
.wishlist-info table {
	width: 100%;
	border-collapse: collapse;
	border-top: 1px solid #DDDDDD;
	border-left: 1px solid #DDDDDD;
	border-right: 1px solid #DDDDDD;
	margin-bottom: 20px;
}
.wishlist-info td {
	padding: 7px;
}
.wishlist-info thead td {
	color: #4D4D4D;
	font-weight: bold;
	background-color: #F7F7F7;
	border-bottom: 1px solid #DDDDDD;
}
.wishlist-info thead .image {
	text-align: center;
}
.wishlist-info thead .name, .wishlist-info thead .model, .wishlist-info thead .stock {
	text-align: left;
}
.wishlist-info thead .quantity, .wishlist-info thead .price, .wishlist-info thead .total, .wishlist-info thead .action {
	text-align: right;
}
.wishlist-info tbody td {
	vertical-align: top;
	border-bottom: 1px solid #DDDDDD;
}
.wishlist-info tbody .image img {
	border: 1px solid #DDDDDD;
}
.wishlist-info tbody .image {
	text-align: center;
}
.wishlist-info tbody .name, .wishlist-info tbody .model, .wishlist-info tbody .stock {
	text-align: left;
}
.wishlist-info tbody .quantity, .wishlist-info tbody .price, .wishlist-info tbody .total, .wishlist-info tbody .action {
	text-align: right;
}
.wishlist-info tbody .price s {
	color: #F00;
}
.wishlist-info tbody .action img {
	cursor: pointer;
}




/* content */

#account_menu {
	float:left;
	width:300px;	
}
#account_content {
	float:left;
	width:670px;	
}

#account_links {
	margin: 20px;
}

#customer_wishlist {
	margin: 20px;
}

#customer_edit {
	margin: 20px;
}

#customer_password {
	margin: 20px; 
}

#customer_addresslist {
	margin: 20px; 
}

#customer_address_insert {
	margin: 20px; 
}

#content .content {
	padding: 10px;
	overflow: auto;
	margin-bottom: 20px;
	border: 1px solid #EEEEEE;
}
#content .content .left {
	float: left;
	width: 49%;
}
#content .content .right {
	float: right;
	width: 49%;
}

