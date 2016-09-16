<?php
	function hashtag($string) {
		$htag = "#";
		$arr = explode(" ",$string);
		$arrc = count($arr);
		$i = 0;

		while ($i < $arrc) {
			if (substr($arr[$i], 0, 1) === $htag) {
				$arr[$i] = "<a href='#'>".$arr[$i]."</a>";
			}

			$i++;
		}

		$string = implode(" ", $arr);
		return $string;
	}
	echo hashtag("This is a #test");

	function findLink($string2) {
		$link = "http://";
		$link2 = "https://";
		$link3 = "www.";
		$arr2 = explode(" ",$string2);
		$arrc2 = count($arr2);
		$i = 0;

		while ($i < $arrc2) {
			if (substr($arr2[$i], 0, 7) === $link || substr($arr2[$i], 0, 8) === $link2 || substr($arr2[$i], 0, 4) === $link3) {
				if (substr($arr2[$i], 0, 4) === $link3) {
					$arr2[$i] = "http://".$arr2[$i];
				}
				$arr2[$i] = "<a href='$arr2[$i]'>".$arr2[$i]."</a>";
			}

			$i++;
		}

		$string2 = implode(" ", $arr2);
		return $string2;
	}
	echo "<br><br>";
	echo findLink("This is a test link, http://test.com");
?>